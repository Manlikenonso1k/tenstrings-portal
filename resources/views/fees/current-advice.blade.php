<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Current Payment Advice</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800">
<div class="max-w-4xl mx-auto p-6 md:p-10">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Current Payment Advice</h1>
        <a href="{{ \App\Filament\Portal\Pages\PaymentsPage::getUrl(panel: 'portal') }}" class="text-sm text-blue-600 hover:underline">Back to Payments</a>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-3 text-sm text-green-700">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-3 text-sm text-red-700">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (! $advice)
        <div class="rounded-lg border bg-white p-6">
            <p class="text-slate-600">No pending advice found.</p>
            <a href="{{ route('fees.generate') }}" class="inline-block mt-4 text-blue-600 hover:underline">Generate fees now</a>
        </div>
    @else
        <div class="rounded-lg border bg-white p-6" id="printable-advice">
            <h2 class="text-xl font-semibold mb-4">Tenstrings Music Institute - Payment Advice</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <div><span class="text-slate-500">Student:</span> {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</div>
                <div><span class="text-slate-500">Student No:</span> {{ $student->student_number }}</div>
                <div><span class="text-slate-500">Course:</span> {{ $advice->course?->name ?? 'N/A' }}</div>
                <div><span class="text-slate-500">Quarter:</span> {{ $advice->quarter_name }}</div>
                <div><span class="text-slate-500">Status:</span> {{ strtoupper($advice->status) }}</div>
                <div><span class="text-slate-500">Generated:</span> {{ optional($advice->generated_at)->format('Y-m-d H:i') }}</div>
            </div>

            <div class="mt-6 border-t pt-4">
                <div class="flex items-center justify-between text-sm">
                    <span>Tuition Fee</span>
                    <span>₦{{ number_format((float) $advice->amount, 2) }}</span>
                </div>
                <div class="flex items-center justify-between mt-2 text-lg font-semibold">
                    <span>Total</span>
                    <span>₦{{ number_format((float) $advice->amount, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <form method="POST" action="{{ route('fees.pay-online') }}">
                @csrf
                <button type="submit" class="rounded-md bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">Pay Online</button>
            </form>
            <button onclick="window.print()" class="rounded-md border border-slate-300 px-4 py-2 hover:bg-slate-100">Print Advice</button>
        </div>
    @endif
</div>
</body>
</html>
