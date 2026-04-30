<x-filament-panels::page>
    @if (session('status'))
        <x-filament::section>
            <div class="text-sm text-green-700">{{ session('status') }}</div>
        </x-filament::section>
    @endif

    @if (session('error'))
        <x-filament::section>
            <div class="text-sm text-red-700">{{ session('error') }}</div>
        </x-filament::section>
    @endif

    <x-filament::section heading="Outstanding Fees">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr><th class="text-left p-2">Course</th><th class="text-left p-2">Outstanding</th><th class="text-left p-2">Due Date</th><th class="text-left p-2">Action</th></tr></thead>
                <tbody>
                    @forelse($fees as $fee)
                        <tr class="border-t">
                            <td class="p-2">{{ $fee->course?->name ?? 'N/A' }}</td>
                            <td class="p-2">₦{{ number_format((float) $fee->outstanding_balance, 2) }}</td>
                            <td class="p-2">{{ $fee->due_date?->toDateString() ?? 'N/A' }}</td>
                            <td class="p-2">
                                <x-filament::button color="primary" wire:click="payFee({{ $fee->id }})" wire:loading.attr="disabled">
                                    Pay Now
                                </x-filament::button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-2 text-gray-500">No outstanding fees.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>

    <x-filament::section>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr><th class="text-left p-2">Payment ID</th><th class="text-left p-2">Date</th><th class="text-left p-2">Amount</th><th class="text-left p-2">Status</th></tr></thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr class="border-t"><td class="p-2">{{ $payment->payment_number }}</td><td class="p-2">{{ $payment->payment_date }}</td><td class="p-2">₦{{ number_format((float) $payment->amount_paid, 2) }}</td><td class="p-2">{{ strtoupper($payment->payment_status) }}</td></tr>
                    @empty
                        <tr><td colspan="4" class="p-2 text-gray-500">No payments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>
