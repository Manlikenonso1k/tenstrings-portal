<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $gatewayLabel }} Payment Step</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-b from-slate-50 via-white to-slate-100 text-slate-800 antialiased">
<div class="relative overflow-hidden">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(37,99,235,0.08),_transparent_38%),radial-gradient(circle_at_bottom_left,_rgba(14,165,233,0.08),_transparent_32%)]"></div>

    <main class="relative mx-auto flex min-h-screen max-w-5xl items-center px-6 py-10 md:px-10 lg:px-12">
        <div class="w-full">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-medium uppercase tracking-[0.24em] text-slate-500">Two-step payment</p>
                    <div class="space-y-1">
                        <h1 class="text-3xl font-semibold tracking-tight text-slate-950 md:text-4xl">Paystack Titan Payment</h1>
                        <p class="max-w-2xl text-sm leading-6 text-slate-500 md:text-base">
                            Review the advice below, choose the amount to pay, and continue to the secure payment gateway.
                        </p>
                    </div>
                </div>

                <a href="{{ route('fees.advice.current') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
                    Back to Advice
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50/80 p-4 text-sm text-rose-700 shadow-sm">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
                <section class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-[0_20px_60px_rgba(15,23,42,0.06)] backdrop-blur md:p-8">
                    <div class="mb-6 flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Student summary</p>
                            <h2 class="mt-1 text-xl font-semibold text-slate-950">Payment overview</h2>
                        </div>
                        <div class="rounded-2xl bg-blue-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-blue-700">
                            Secure
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-slate-50/80 p-5 shadow-sm">
                        <div class="grid gap-4 md:grid-cols-3">
                            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                                <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-400">Name</p>
                                <p class="mt-2 text-base font-semibold text-slate-900">Test Student</p>
                            </div>
                            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                                <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-400">Advice Period</p>
                                <p class="mt-2 text-base font-semibold text-slate-900">Q2-2026</p>
                            </div>
                            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                                <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-400">Outstanding Balance</p>
                                <p class="mt-2 text-xl font-semibold text-slate-950">₦1,800,000.00</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-[0_20px_60px_rgba(15,23,42,0.06)] backdrop-blur md:p-8">
                    <div class="mb-6">
                        <p class="text-sm font-medium text-slate-500">Payment form</p>
                        <h2 class="mt-1 text-xl font-semibold text-slate-950">Amount to Pay</h2>
                    </div>

                    <form action="{{ route('fees.pay.submit', $gateway) }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="space-y-2">
                            <label for="amount" class="block text-sm font-medium text-slate-700">Amount to Pay</label>
                            <input
                                id="amount"
                                name="amount"
                                type="number"
                                min="1"
                                max="{{ (float) $outstandingBalance }}"
                                step="0.01"
                                value="{{ old('amount', $defaultAmount) }}"
                                class="block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-base text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                required
                            >
                            <p class="text-xs leading-5 text-slate-500">Pre-filled with the outstanding balance for a faster checkout.</p>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row">
                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                                Continue to Paystack Titan
                            </button>
                            <a href="{{ route('fees.generate') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
                                Back to Generate Fees
                            </a>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </main>
</div>
</body>
</html>