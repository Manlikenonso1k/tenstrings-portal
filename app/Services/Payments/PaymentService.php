<?php

namespace App\Services\Payments;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentCourseFee;
use App\Services\Payments\Gateways\PaystackTitanGateway;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class PaymentService
{
    public function __construct(
        private readonly QuarterResolver $quarterResolver,
        private readonly DocumentService $documentService,
        private readonly DatabaseManager $db,
    ) {
    }

    public function initializePayment(string $gateway, array $data): array
    {
        $student = Student::query()->with('user')->findOrFail((int) $data['student_id']);
        $quarter = (string) ($data['quarter_name'] ?? $this->quarterResolver->currentQuarter());
        $invoiceAmount = (float) ($data['invoice_amount'] ?? $data['amount']);

        $invoice = Invoice::query()->firstOrCreate(
            [
                'student_id' => $student->id,
                'quarter_name' => $quarter,
            ],
            [
                'amount' => $invoiceAmount,
                'status' => 'unpaid',
            ]
        );

        if ($invoice->status !== 'paid' && (float) $invoice->amount < $invoiceAmount) {
            $invoice->update(['amount' => $invoiceAmount]);
        }

        // Regenerate invoice PDF before payment initialization to keep it authoritative.
        $invoicePath = $this->documentService->generateInvoicePdf($invoice);

        $reference = (string) ($data['reference'] ?? 'TSI-' . strtoupper(Str::random(12)));

        $payment = Payment::query()->create([
            'user_id' => $student->user_id,
            'student_id' => $student->id,
            'invoice_id' => $invoice->id,
            'gateway' => $gateway,
            'reference' => $reference,
            'course_id' => $data['course_id'] ?? null,
            'amount' => (float) $data['amount'],
            'status' => 'pending',
            'payment_status' => 'pending',
            'amount_paid' => 0,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'transfer',
            'metadata' => [
                'invoice_path' => $invoicePath,
                'quarter_name' => $quarter,
                'student_id' => $student->id,
                'invoice_id' => $invoice->id,
                'course_id' => $data['course_id'] ?? null,
            ],
        ]);

        $gatewayClient = $this->gateway($gateway);

        $response = $gatewayClient->initializePayment([
            'email' => $student->email,
            'amount' => (float) $data['amount'],
            'reference' => $reference,
            'metadata' => [
                'student_id' => $student->id,
                'invoice_id' => $invoice->id,
                'quarter_name' => $quarter,
                'course_id' => $data['course_id'] ?? null,
            ],
            'callback_url' => $data['callback_url'] ?? null,
        ]);

        $payment->update([
            'gateway_response' => $response['body'] ?? [],
            'status' => ($response['ok'] ?? false) ? 'processing' : 'failed',
        ]);

        Log::info('Payment initialized.', [
            'gateway' => $gateway,
            'student_id' => $student->id,
            'reference' => $reference,
            'status_code' => $response['status'] ?? null,
        ]);

        return [
            'payment' => $payment->fresh(),
            'invoice' => $invoice,
            'gateway_response' => $response,
        ];
    }

    public function verifyPayment(string $gateway, string $reference): array
    {
        $response = $this->gateway($gateway)->verifyPayment($reference);

        Log::info('Payment verification requested.', [
            'gateway' => $gateway,
            'reference' => $reference,
            'status_code' => $response['status'] ?? null,
        ]);

        return $response;
    }

    public function handleWebhook(string $gateway, array $payload): array
    {
        $normalized = $this->gateway($gateway)->handleWebhook($payload);

        Log::info('Payment webhook received.', [
            'gateway' => $gateway,
            'event' => $normalized['event'] ?? null,
            'reference' => $normalized['reference'] ?? null,
        ]);

        if (($normalized['reference'] ?? '') === '') {
            return ['processed' => false, 'reason' => 'missing_reference'];
        }

        $result = $this->db->transaction(function () use ($gateway, $normalized) {
            $createdNew = false;
            $payment = Payment::query()->where('reference', $normalized['reference'])->lockForUpdate()->first();

            if (! $payment) {
                $studentId = $normalized['student_id'] ?? null;

                if (! $studentId && ! empty($normalized['customer_email'])) {
                    $studentId = Student::query()->where('email', $normalized['customer_email'])->value('id');
                }

                $student = $studentId ? Student::query()->find($studentId) : null;

                $payment = Payment::query()->create([
                    'user_id' => $student?->user_id,
                    'student_id' => $student?->id,
                    'invoice_id' => $normalized['invoice_id'] ?? null,
                    'course_id' => $normalized['course_id'] ?? data_get($normalized, 'metadata.course_id'),
                    'gateway' => $gateway,
                    'reference' => $normalized['reference'],
                    'amount' => (float) ($normalized['amount'] ?? 0),
                    'status' => (string) ($normalized['status'] ?? 'processing'),
                    'payment_status' => $this->legacyStatus((string) ($normalized['status'] ?? 'processing')),
                    'amount_paid' => (string) ($normalized['status'] ?? '') === 'success' ? (float) ($normalized['amount'] ?? 0) : 0,
                    'payment_date' => now()->toDateString(),
                    'payment_method' => 'transfer',
                    'gateway_response' => $normalized['gateway_response'] ?? [],
                    'metadata' => $normalized['metadata'] ?? [],
                    'processed_at' => now(),
                ]);

                $createdNew = true;
            }

            if (! $createdNew && in_array($payment->status, ['success', 'failed'], true)) {
                return ['processed' => true, 'idempotent' => true, 'payment_id' => $payment->id];
            }

            $newStatus = (string) ($normalized['status'] ?? 'processing');
            $update = [
                'gateway_response' => $normalized['gateway_response'] ?? [],
                'metadata' => array_merge((array) $payment->metadata, (array) ($normalized['metadata'] ?? [])),
                'status' => $newStatus,
                'payment_status' => $this->legacyStatus($newStatus),
                'processed_at' => now(),
            ];

            if ($newStatus === 'success') {
                $update['amount_paid'] = (float) ($normalized['amount'] ?? $payment->amount ?? 0);
            }

            $payment->update($update);

            if ($newStatus === 'success') {
                if ($payment->invoice) {
                    $successfulAmount = (float) Payment::query()
                        ->where('invoice_id', $payment->invoice_id)
                        ->where('status', 'success')
                        ->sum('amount_paid');

                    $payment->invoice->update([
                        'status' => $successfulAmount >= (float) $payment->invoice->amount ? 'paid' : 'unpaid',
                    ]);
                }

                $this->syncCourseFeeAndStudentSnapshot($payment->fresh());

                $receiptPath = $this->documentService->generateReceiptPdf($payment->fresh());
                $payment->update([
                    'metadata' => array_merge((array) $payment->metadata, ['receipt_path' => $receiptPath]),
                ]);
            }

            return ['processed' => true, 'idempotent' => false, 'payment_id' => $payment->id];
        });

        return $result;
    }

    public function createFutureQuarterInvoice(int $studentId, float $amount, int $futureOffset = 1): Invoice
    {
        $quarter = $this->quarterResolver->futureQuarter($futureOffset);

        $invoice = Invoice::query()->firstOrCreate(
            [
                'student_id' => $studentId,
                'quarter_name' => $quarter,
            ],
            [
                'amount' => $amount,
                'status' => 'unpaid',
            ]
        );

        $this->documentService->generateInvoicePdf($invoice);

        return $invoice;
    }

    public function gateway(string $gateway): PaystackTitanGateway
    {
        return match (strtolower($gateway)) {
            'paystack', 'paystack_titan', 'paystack-titan' => app(PaystackTitanGateway::class),
            default => throw new RuntimeException('Unsupported gateway: ' . $gateway),
        };
    }

    private function legacyStatus(string $status): string
    {
        return match ($status) {
            'success' => 'paid',
            'failed' => 'pending',
            default => 'pending',
        };
    }

    private function syncCourseFeeAndStudentSnapshot(Payment $payment): void
    {
        if (! $payment->student_id) {
            return;
        }

        $courseId = $payment->course_id ?: data_get($payment->metadata, 'course_id');

        if ($courseId) {
            $fee = StudentCourseFee::query()
                ->where('student_id', $payment->student_id)
                ->where('course_id', $courseId)
                ->lockForUpdate()
                ->first();

            if ($fee) {
                $successfulCoursePayments = (float) Payment::query()
                    ->where('student_id', $payment->student_id)
                    ->where('course_id', $courseId)
                    ->where(function ($query) {
                        $query->where('status', 'success')
                            ->orWhere('payment_status', 'paid');
                    })
                    ->sum('amount_paid');

                $fee->amount_paid = min((float) $fee->total_course_fee, $successfulCoursePayments);
                $fee->outstanding_balance = max(0, (float) $fee->total_course_fee - (float) $fee->amount_paid);
                $fee->status = $fee->outstanding_balance <= 0
                    ? 'paid'
                    : ((float) $fee->amount_paid > 0 ? 'partial' : 'pending');
                $fee->save();
            }
        }

        $student = Student::query()->find($payment->student_id);

        if (! $student) {
            return;
        }

        $totals = StudentCourseFee::query()
            ->where('student_id', $payment->student_id)
            ->selectRaw('COALESCE(SUM(total_course_fee), 0) as total_fee, COALESCE(SUM(amount_paid), 0) as paid, COALESCE(SUM(outstanding_balance), 0) as outstanding')
            ->first();

        $student->update([
            'total_balance' => (float) ($totals->total_fee ?? 0),
            'fees_paid' => (float) ($totals->paid ?? 0),
            'balance_due' => (float) ($totals->outstanding ?? 0),
        ]);
    }
}
