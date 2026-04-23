<x-filament-panels::page>
    @if (session('status'))
        <x-filament::section>
            <p class="text-sm text-green-700">{{ session('status') }}</p>
        </x-filament::section>
    @endif

    @if ($errors->any())
        <x-filament::section>
            <ul class="text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-filament::section>
    @endif

    <div class="grid gap-4 md:grid-cols-3">
        <x-filament::section>
            <p class="text-xs text-gray-500">Total Balance</p>
            <p class="text-xl font-semibold">₦{{ number_format((float) ($student?->total_balance ?? 0), 2) }}</p>
        </x-filament::section>
        <x-filament::section>
            <p class="text-xs text-gray-500">Balance Due</p>
            <p class="text-xl font-semibold">₦{{ number_format((float) ($student?->balance_due ?? 0), 2) }}</p>
        </x-filament::section>
        <x-filament::section>
            <p class="text-xs text-gray-500">Fees Paid</p>
            <p class="text-xl font-semibold">₦{{ number_format((float) ($student?->fees_paid ?? 0), 2) }}</p>
        </x-filament::section>
    </div>

    <x-filament::section>
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs text-gray-500">Outstanding Balance</p>
                <p class="text-lg font-semibold">₦{{ number_format((float) $outstandingBalance, 2) }}</p>
            </div>
            <form method="POST" action="{{ route('portal.payments.pay_outstanding') }}" class="flex flex-col gap-2 md:flex-row md:items-center">
                @csrf
                <input
                    type="number"
                    name="amount"
                    min="1"
                    step="0.01"
                    max="{{ (float) $outstandingBalance }}"
                    placeholder="Enter amount"
                    class="fi-input block w-full rounded-lg border-gray-300 text-sm"
                    required
                >
                <x-filament::button type="submit" color="primary" :disabled="$outstandingBalance <= 0">
                    Pay Outstanding
                </x-filament::button>
            </form>
        </div>
    </x-filament::section>

    <x-filament::section>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr><th class="text-left p-2">Payment ID</th><th class="text-left p-2">Receipt No.</th><th class="text-left p-2">Date</th><th class="text-left p-2">Amount</th><th class="text-left p-2">Status</th><th class="text-left p-2">Action</th></tr></thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr class="border-t">
                            <td class="p-2">{{ $payment->payment_number }}</td>
                            <td class="p-2">{{ $payment->receipt_number ?: 'Pending' }}</td>
                            <td class="p-2">{{ optional($payment->processed_at ?: $payment->payment_date)?->format('Y-m-d H:i') }}</td>
                            <td class="p-2">₦{{ number_format((float) ($payment->amount_paid ?: $payment->amount), 2) }}</td>
                            <td class="p-2">{{ strtoupper((string) ($payment->status ?? $payment->payment_status)) }}</td>
                            <td class="p-2">
                                @if (($payment->status ?? null) === 'success')
                                    <a href="{{ route('portal.payments.receipt', $payment) }}" target="_blank" class="text-primary-600 hover:underline">Print Receipt</a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-2 text-gray-500">No payments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>
