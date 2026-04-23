<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\Payments\DocumentService;
use App\Services\Payments\PaymentService;
use Illuminate\Http\JsonResponse;
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
}
