<?php

namespace App\Http\Controllers;

use App\Filament\Portal\Pages\PaymentsPage;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\StudentCourseFee;
use App\Services\Payments\DocumentService;
use App\Services\Payments\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly DocumentService $documentService,
    ) {
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
        $student = $request->user()?->student;

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
            'course_id' => $primaryFee?->course_id,
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

    public function callback(): RedirectResponse
    {
        return redirect($this->portalPaymentsUrl())
            ->with('status', 'Payment submitted. Confirmation will update automatically after webhook processing.');
    }

    private function portalPaymentsUrl(): string
    {
        return PaymentsPage::getUrl(panel: 'portal');
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
        $isOwner = $user?->student && (int) $user->student->id === (int) $payment->student_id;
        $isAdmin = in_array($user?->role, ['super_admin', 'admin'], true);

        abort_unless($isOwner || $isAdmin, 403);

        $path = (string) data_get($payment->metadata, 'receipt_path', '');

        if ($path === '' || ! Storage::disk('local')->exists($path)) {
            $path = $this->documentService->generateReceiptPdf($payment);
        }

        return response()->download(Storage::disk('local')->path($path), 'receipt_' . ($payment->receipt_number ?: $payment->reference) . '.pdf');
    }
}
