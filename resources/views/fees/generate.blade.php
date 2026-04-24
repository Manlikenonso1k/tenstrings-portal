<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generate Fees</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
<div class="max-w-4xl mx-auto p-6 md:p-10">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Generate Fees</h1>
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

    @if ($pendingAdvice)
        <div class="mb-6 rounded-md border border-amber-300 bg-amber-50 p-4">
            <p class="text-sm font-medium text-amber-800">You already have a pending advice.</p>
            <p class="text-sm text-amber-700 mt-1">{{ $pendingAdvice->quarter_name }} • ₦{{ number_format((float) $pendingAdvice->amount, 2) }}</p>
            <a href="{{ route('fees.advice.current') }}" class="inline-block mt-2 text-sm text-blue-600 hover:underline">View pending advice</a>
        </div>
    @endif

    <form action="{{ route('fees.generate.store') }}" method="POST" class="rounded-lg bg-white border p-6">
        @csrf

        <div class="mb-4">
            <label class="block text-sm mb-2 font-medium">Year</label>
            <input name="year" type="number" value="{{ old('year', $year) }}" min="2020" max="2100" class="w-full rounded-md border border-slate-300 px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm mb-2 font-medium">Select Quarter Intake Month</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach ($quarters as $quarter)
                    <label class="rounded-md border p-4 flex items-center justify-between cursor-pointer hover:border-blue-400">
                        <span class="font-medium">{{ $quarter['label'] }}</span>
                        <input type="radio" name="quarter_month" value="{{ $quarter['month'] }}" @checked(old('quarter_month') == $quarter['month']) required>
                    </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="mt-6 inline-flex rounded-md bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">
            Generate Advice
        </button>
    </form>
</div>
</body>
</html>
