<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->id }}</title>
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
        <div class="muted">Quarterly Student Invoice</div>
    </div>

    <div class="card">
        <strong>Invoice #:</strong> INV-{{ $invoice->id }}<br>
        <strong>Student:</strong> {{ trim(($invoice->student->first_name ?? '') . ' ' . ($invoice->student->last_name ?? '')) }}<br>
        <strong>Matric No:</strong> {{ $invoice->student->student_number ?? 'N/A' }}<br>
        <strong>Quarter:</strong> {{ $invoice->quarter_name }}<br>
        <strong>Status:</strong> {{ strtoupper($invoice->status) }}<br>
        <strong>Date:</strong> {{ $invoice->created_at?->format('Y-m-d H:i') }}
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
                <td>Tuition for {{ $invoice->quarter_name }}</td>
                <td>₦{{ number_format((float) $invoice->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">Total Due: ₦{{ number_format((float) $invoice->amount, 2) }}</div>
</body>
</html>
