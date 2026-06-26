<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\StudentCourseFee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PaymentApiController extends Controller
{
    /**
     * GET /api/v1/payments
     *
     * Returns the student's paginated payment history.
     * Uses the 'status' column (modern: success/failed/processing) as canonical.
     */
    public function index(Request $request): JsonResponse
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');

        $payments = Payment::where('student_id', $student->id)
            ->with('course:id,name,code')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'data' => $payments->map(fn ($payment) => $this->formatPayment($payment)),
            'meta' => [
                'current_page' => $payments->currentPage(),
                'last_page'    => $payments->lastPage(),
                'total'        => $payments->total(),
                'per_page'     => $payments->perPage(),
            ],
        ]);
    }

    /**
     * GET /api/v1/payments/fee-status
     *
     * Returns the student's course fee breakdown and outstanding balances.
     */
    public function feeStatus(Request $request): JsonResponse
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');

        $fees = StudentCourseFee::where('student_id', $student->id)
            ->with('course:id,name,code')
            ->get()
            ->map(fn ($fee) => [
                'course'              => [
                    'id'   => $fee->course?->id,
                    'name' => $fee->course?->name,
                    'code' => $fee->course?->code,
                ],
                'total_course_fee'    => (float) $fee->total_course_fee,
                'amount_paid'         => (float) $fee->amount_paid,
                'outstanding_balance' => (float) $fee->outstanding_balance,
                'status'              => $fee->status, // paid | partial | pending
                'due_date'            => $fee->due_date?->toDateString(),
            ]);

        // Also include the top-level student financial summary
        return response()->json([
            'data' => [
                'summary' => [
                    'total_fees_paid'   => (float) ($student->fees_paid ?? 0),
                    'total_balance_due' => (float) ($student->balance_due ?? 0),
                    'total_balance'     => (float) ($student->total_balance ?? 0),
                ],
                'course_fees' => $fees,
            ],
        ]);
    }

    /**
     * GET /api/v1/payments/{payment}
     *
     * Returns the detail for a single payment.
     * Ensures the payment belongs to the authenticated student.
     */
    public function show(Request $request, Payment $payment): JsonResponse
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');
        abort_if($payment->student_id !== $student->id, 403, 'Access denied.');

        $payment->load(['course:id,name,code', 'invoice:id,invoice_number']);

        return response()->json(['data' => $this->formatPayment($payment, detailed: true)]);
    }

    /**
     * GET /api/v1/payments/{payment}/receipt
     *
     * Returns a temporary signed URL for downloading the payment receipt PDF.
     * Returns 404 if no receipt has been generated yet.
     */
    public function receipt(Request $request, Payment $payment): JsonResponse
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');
        abort_if($payment->student_id !== $student->id, 403, 'Access denied.');

        if ($payment->status !== 'success') {
            return response()->json([
                'message' => 'Receipt is only available for successful payments.',
            ], 422);
        }

        $receiptPath = data_get($payment->metadata, 'receipt_path');

        if (! $receiptPath) {
            return response()->json([
                'message' => 'Receipt has not been generated yet.',
            ], 404);
        }

        // Generate a signed URL valid for 10 minutes
        $url = URL::temporarySignedRoute(
            'documents.receipts.download',
            now()->addMinutes(10),
            ['payment' => $payment->id]
        );

        return response()->json([
            'data' => [
                'receipt_url'    => $url,
                'receipt_number' => $payment->receipt_number,
                'expires_in'     => '10 minutes',
            ],
        ]);
    }

    /**
     * POST /api/v1/payments/initiate/{gateway}
     *
     * Initiates a payment via the specified gateway (paystack-titan or tgipay).
     * The mobile app should redirect the user to the returned payment_url
     * in an in-app browser (WebView or Linking.openURL).
     */
    public function initiate(Request $request, string $gateway): JsonResponse
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');

        $request->validate([
            'amount'     => ['required', 'numeric', 'min:100'],
            'invoice_id' => ['nullable', 'integer', 'exists:invoices,id'],
        ]);

        // For now, return 501 — full gateway initiation requires
        // routing through the existing PaymentService to avoid code duplication.
        // TODO: Extract PaymentService::initialize() into a shared service method
        //       and call it from here, passing a mobile callback URL.
        return response()->json([
            'message' => 'Payment initiation via API is coming soon. Please use the web portal to make payments.',
        ], 501);
    }

    /**
     * Formats a Payment model into a clean API array.
     */
    private function formatPayment(Payment $payment, bool $detailed = false): array
    {
        $base = [
            'id'             => $payment->id,
            'payment_number' => $payment->payment_number,
            'amount'         => (float) ($payment->amount ?? $payment->amount_paid),
            'amount_paid'    => (float) ($payment->amount_paid ?? 0),
            'status'         => $payment->status,         // modern: success|failed|processing
            'payment_status' => $payment->payment_status, // legacy: paid|partial|pending
            'gateway'        => $payment->gateway,
            'payment_method' => $payment->payment_method,
            'receipt_number' => $payment->receipt_number,
            'payment_date'   => $payment->payment_date?->toDateString(),
            'processed_at'   => $payment->processed_at?->toIso8601String(),
            'course'         => $payment->course ? [
                'id'   => $payment->course->id,
                'name' => $payment->course->name,
                'code' => $payment->course->code,
            ] : null,
        ];

        if ($detailed) {
            $base['reference'] = $payment->reference;
            $base['notes']     = $payment->notes;
            $base['invoice']   = $payment->invoice ? [
                'id'             => $payment->invoice->id,
                'invoice_number' => $payment->invoice->invoice_number,
            ] : null;
        }

        return $base;
    }
}
