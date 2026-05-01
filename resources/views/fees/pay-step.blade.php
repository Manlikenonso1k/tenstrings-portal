<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $gatewayLabel }} Payment Step</title>
</head>
<body class="bg-slate-50 text-slate-800">
<div class="max-w-3xl mx-auto p-6 md:p-10">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500">Two-step payment</p>
            <h1 class="text-2xl font-semibold">{{ $gatewayLabel }} Payment</h1>
        </div>
        <a href="{{ route('fees.advice.current') }}" class="text-sm text-blue-600 hover:underline">Back to Advice</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-3 text-sm text-red-700">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-lg border bg-white p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div><span class="text-slate-500">Student:</span> {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</div>
            <div><span class="text-slate-500">Advice:</span> {{ $pendingAdvice->quarter_name }}</div>
            <div><span class="text-slate-500">Outstanding:</span> ₦{{ number_format((float) $outstandingBalance, 2) }}</div>
            <div><span class="text-slate-500">Advice Amount:</span> ₦{{ number_format((float) $pendingAdvice->amount, 2) }}</div>
        </div>
    </div>

    <form action="{{ route('fees.pay.submit', $gateway) }}" method="POST" class="rounded-lg bg-white border p-6">
        @csrf

        <div class="mb-4">
            <label class="block text-sm mb-2 font-medium">Amount to Pay</label>
            <input
                name="amount"
                type="number"
                min="1"
                max="{{ (float) $outstandingBalance }}"
                step="0.01"
                value="{{ old('amount', $defaultAmount) }}"
                class="w-full rounded-md border border-slate-300 px-3 py-2"
                required
            >
            <p class="mt-2 text-xs text-slate-500">You can pay any amount up to the outstanding balance.</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex rounded-md bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">Continue to {{ $gatewayLabel }}</button>
            <a href="{{ route('fees.generate') }}" class="inline-flex rounded-md border border-slate-300 px-4 py-2 hover:bg-slate-100">Back to Generate Fees</a>
        </div>
    </form>
</div>
</body>
</html>