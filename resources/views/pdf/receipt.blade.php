<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt {{ $payment->reference }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 13px; }
        .header { margin-bottom: 18px; }
        .brand { font-size: 22px; font-weight: 700; }
        .muted { color: #475569; }
        .card { border: 1px solid #cbd5e1; border-radius: 8px; padding: 14px; margin: 12px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; }
        th { background: #e2e8f0; }
        .total { font-size: 16px; font-weight: 700; text-align: right; margin-top: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">Tenstrings Music Institute</div>
        <div class="muted">Payment Receipt</div>
    </div>

    <div class="card">
        <strong>Receipt Ref:</strong> {{ $payment->reference }}<br>
        <strong>Student:</strong> {{ trim(($payment->student->first_name ?? '') . ' ' . ($payment->student->last_name ?? '')) }}<br>
        <strong>Matric No:</strong> {{ $payment->student->student_number ?? 'N/A' }}<br>
        <strong>Quarter:</strong> {{ data_get($payment->metadata, 'quarter_name', $payment->invoice?->quarter_name ?? 'N/A') }}<br>
        <strong>Payment Date:</strong> {{ $payment->processed_at?->format('Y-m-d H:i') ?? $payment->updated_at?->format('Y-m-d H:i') }}<br>
        <strong>Status:</strong> {{ strtoupper($payment->status ?? 'PENDING') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Quarterly Tuition Payment</td>
                <td>₦{{ number_format((float) ($payment->amount_paid ?: $payment->amount), 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">Paid: ₦{{ number_format((float) ($payment->amount_paid ?: $payment->amount), 2) }}</div>
</body>
</html>
