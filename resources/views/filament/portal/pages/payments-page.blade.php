<x-filament-panels::page>
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
