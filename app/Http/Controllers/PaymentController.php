<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PortalSetting;
use App\Models\Student;
use App\Models\StudentCourseFee;
use App\Services\Payments\DocumentService;
use App\Services\Payments\PaymentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PaymentController extends Controller
{
    private $paymentService;

    private $documentService;

    public function __construct(
        PaymentService $paymentService,
        DocumentService $documentService
    ) {
        $this->paymentService = $paymentService;
        $this->documentService = $documentService;
    }

    public function initialize(Request $request, string $gateway): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'invoice_amount' => ['nullable', 'numeric', 'min:1'],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
            'quarter_name' => ['nullable', 'string', 'max:30'],
            'reference' => ['nullable', 'string', 'max:100'],
            'callback_url' => ['nullable', 'url'],
        ]);

        $result = $this->paymentService->initializePayment($gateway, $validated);

        return response()->json([
            'ok' => true,
            'message' => 'Payment initialized successfully.',
            'data' => $result,
        ], 200);
    }

    public function verify(string $gateway, string $reference): JsonResponse
    {
        $result = $this->paymentService->verifyPayment($gateway, $reference);

        return response()->json([
            'ok' => true,
            'message' => 'Payment verification fetched.',
            'data' => $result,
        ], 200);
    }

    public function payOutstanding(Request $request): RedirectResponse
    {
        $user = $request->user();
        $student = $user && $user->student ? $user->student : null;

        if (! $student) {
            abort(403);
        }

        $outstanding = (float) StudentCourseFee::query()
            ->where('student_id', $student->id)
            ->sum('outstanding_balance');

        if ($outstanding <= 0) {
            return redirect($this->portalPaymentsUrl())->with('status', 'No outstanding balance found.');
        }

        if (! is_string($student->email) || ! filter_var($student->email, FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors([
                'amount' => 'Cannot start payment because your student email is missing or invalid. Please contact admin.',
            ]);
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $amount = (float) $validated['amount'];

        if ($amount > $outstanding) {
            return back()->withErrors([
                'amount' => 'Amount cannot be greater than outstanding balance.',
            ]);
        }

        $primaryFee = StudentCourseFee::query()
            ->where('student_id', $student->id)
            ->where('outstanding_balance', '>', 0)
            ->orderByDesc('outstanding_balance')
            ->first();

        $result = $this->paymentService->initializePayment('paystack-titan', [
            'student_id' => (int) $student->id,
            'amount' => $amount,
            'invoice_amount' => $outstanding,
            'course_id' => $primaryFee ? $primaryFee->course_id : null,
            'callback_url' => route('portal.payments.callback'),
        ]);

        $authorizationUrl = data_get($result, 'gateway_response.body.data.authorization_url');

        if (! is_string($authorizationUrl) || $authorizationUrl === '') {
            $gatewayMessage = (string) (
                data_get($result, 'gateway_response.body.message')
                ?? data_get($result, 'gateway_response.body.data.message')
                ?? data_get($result, 'gateway_response.body.error')
                ?? 'Unable to start payment right now. Please try again.'
            );

            return back()->withErrors([
                'amount' => $gatewayMessage,
            ]);
        }

        return redirect()->away($authorizationUrl);
    }

    public function callback(Request $request): RedirectResponse
    {
        $reference = (string) $request->query('ref', '');
        $status = (string) $request->query('status', '');

        if ($reference !== '') {
            $payment = Payment::query()->where('reference', $reference)->first();

            if ($payment && strtolower((string) $payment->gateway) === 'tgipay') {
                $user = $request->user();
                $student = $user && $user->student ? $user->student : null;

                $this->paymentService->reconcileTgiPayPayment($reference, [
                    'student_id' => $payment->student_id ?: ($student ? $student->id : null),
                    'course_id' => $payment->course_id,
                    'invoice_id' => $payment->invoice_id,
                    'amount' => (float) $payment->amount,
                    'customer_email' => (string) ($student ? $student->email : ''),
                    'metadata' => (array) $payment->metadata,
                ], $status);

                return redirect($this->portalPaymentsUrl())
                    ->with('status', 'TGIPAY payment submitted. We are confirming the final status now.');
            }
        }

        return redirect($this->portalPaymentsUrl())
            ->with('status', 'Payment submitted. Confirmation will update automatically after webhook processing.');
    }

    private function portalPaymentsUrl(): string
    {
        return route('filament.portal.pages.payments-page');
    }

    public function downloadInvoice(Invoice $invoice): BinaryFileResponse
    {
        $path = $this->documentService->invoicePath($invoice);

        if (! Storage::disk('local')->exists($path)) {
            $path = $this->documentService->generateInvoicePdf($invoice);
        }

        return response()->download(Storage::disk('local')->path($path), 'invoice_' . $invoice->id . '.pdf');
    }

    public function downloadReceipt(Payment $payment): BinaryFileResponse
    {
        abort_unless($payment->status === 'success', 404);

        $path = (string) data_get($payment->metadata, 'receipt_path', '');

        if ($path === '' || ! Storage::disk('local')->exists($path)) {
            $path = $this->documentService->generateReceiptPdf($payment);
        }

        return response()->download(Storage::disk('local')->path($path), 'receipt_' . $payment->reference . '.pdf');
    }

    public function downloadStudentReceipt(Request $request, Payment $payment): BinaryFileResponse
    {
        abort_unless($payment->status === 'success', 404);

        $user = $request->user();
        $isOwner = $user && $user->student && (int) $user->student->id === (int) $payment->student_id;
        $isAdmin = in_array($user ? $user->role : null, ['super_admin', 'admin'], true);

        abort_unless($isOwner || $isAdmin, 403);

        $path = (string) data_get($payment->metadata, 'receipt_path', '');

        if ($path === '' || ! Storage::disk('local')->exists($path)) {
            $path = $this->documentService->generateReceiptPdf($payment);
        }

        return response()->download(Storage::disk('local')->path($path), 'receipt_' . ($payment->receipt_number ?: $payment->reference) . '.pdf');
    }

    public function resetStudentPayment(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (!($user && $user->isSuperAdmin())) {
            return redirect()->back()->withErrors(['authorization' => 'Only super admins can reset payments.']);
        }

        $settings = PortalSetting::current();
        if (!$settings->allow_payment_reset) {
            return redirect()->back()->withErrors(['payment_reset' => 'Payment reset is disabled. Enable it in portal settings.']);
        }

        $studentId = $request->integer('student_id');
        $courseId = $request->integer('course_id');

        try {
            $student = Student::query()->findOrFail($studentId);

            DB::transaction(function () use ($studentId, $courseId, $student, $user): void {
                Payment::query()
                    ->where('student_id', $studentId)
                    ->where('course_id', $courseId)
                    ->whereIn('status', ['success', 'processing'])
                    ->update([
                        'status' => 'pending',
                        'payment_status' => 'pending',
                        'amount_paid' => 0,
                        'receipt_number' => null,
                        'processed_at' => null,
                    ]);

                StudentCourseFee::query()
                    ->where('student_id', $studentId)
                    ->where('course_id', $courseId)
                    ->update([
                        'amount_paid' => 0,
                        'outstanding_balance' => DB::raw('total_course_fee'),
                        'status' => 'pending',
                    ]);

                $totalPaid = StudentCourseFee::query()
                    ->where('student_id', $studentId)
                    ->sum('amount_paid');

                $totalOutstanding = StudentCourseFee::query()
                    ->where('student_id', $studentId)
                    ->sum('outstanding_balance');

                Student::query()
                    ->where('id', $studentId)
                    ->update([
                        'fees_paid' => $totalPaid,
                        'balance_due' => $totalOutstanding,
                    ]);

                activity()
                    ->causedBy($user)
                    ->performedOn($student)
                    ->log('payment_reset', [
                        'student_id' => $studentId,
                        'course_id' => $courseId,
                        'reset_by' => $user->id,
                    ]);
            });

            return redirect()->back()->with('status', "Payment for student " . $student->user->name . " (Course " . $courseId . ") has been reset successfully.");
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to reset payment: ' . $e->getMessage()]);
        }
    }
}

