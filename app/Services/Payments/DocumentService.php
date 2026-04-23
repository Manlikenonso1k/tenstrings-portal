<?php

namespace App\Services\Payments;

use App\Models\Invoice;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function generateInvoicePdf(Invoice $invoice): string
    {
        $path = $this->invoicePath($invoice);

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice->loadMissing('student'),
        ]);

        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }

    public function generateReceiptPdf(Payment $payment): string
    {
        $payment->loadMissing('student', 'invoice');
        $path = $this->receiptPath($payment);

        $pdf = Pdf::loadView('pdf.receipt', [
            'payment' => $payment,
        ]);

        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }

    public function invoicePath(Invoice $invoice): string
    {
        return 'documents/invoices/invoice_' . $invoice->id . '.pdf';
    }

    public function receiptPath(Payment $payment): string
    {
        return 'documents/receipts/receipt_' . $payment->reference . '.pdf';
    }
}
