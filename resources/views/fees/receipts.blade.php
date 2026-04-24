<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipts</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800">
<div class="max-w-5xl mx-auto p-6 md:p-10">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Payment Receipts</h1>
        <a href="{{ \App\Filament\Portal\Pages\PaymentsPage::getUrl(panel: 'portal') }}" class="text-sm text-blue-600 hover:underline">Back to Payments</a>
    </div>

    <div class="rounded-lg border bg-white overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100">
            <tr>
                <th class="text-left p-3">Receipt No.</th>
                <th class="text-left p-3">Reference</th>
                <th class="text-left p-3">Date</th>
                <th class="text-left p-3">Amount</th>
                <th class="text-left p-3">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($payments as $payment)
                <tr class="border-t">
                    <td class="p-3">{{ $payment->receipt_number ?: 'N/A' }}</td>
                    <td class="p-3">{{ $payment->reference }}</td>
                    <td class="p-3">{{ optional($payment->processed_at ?: $payment->payment_date)->format('Y-m-d H:i') }}</td>
                    <td class="p-3">₦{{ number_format((float) ($payment->amount_paid ?: $payment->amount), 2) }}</td>
                    <td class="p-3">
                        <a href="{{ route('portal.payments.receipt', $payment) }}" target="_blank" class="text-blue-600 hover:underline">Download</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-3 text-slate-500">No successful receipts yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
