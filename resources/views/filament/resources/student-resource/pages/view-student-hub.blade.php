<x-filament-panels::page>
    @php
        $feesPaid = (float) ($this->record->fees_paid ?? 0);
        $balanceDue = (float) ($this->record->balance_due ?? 0);
        $totalBalance = (float) ($this->record->total_balance ?? ($feesPaid + $balanceDue));
        $paymentStatus = $balanceDue <= 0
            ? 'PAID'
            : ($feesPaid > 0 ? 'OWING (PARTIAL)' : 'PENDING');
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <x-filament::section>
            <p class="text-xs text-gray-500">Total Fees</p>
            <p class="text-xl font-semibold">N{{ number_format($totalBalance, 2) }}</p>
        </x-filament::section>
        <x-filament::section>
            <p class="text-xs text-gray-500">Amount Paid</p>
            <p class="text-xl font-semibold">N{{ number_format($feesPaid, 2) }}</p>
        </x-filament::section>
        <x-filament::section>
            <p class="text-xs text-gray-500">Amount Owed</p>
            <p class="text-xl font-semibold">N{{ number_format($balanceDue, 2) }}</p>
            <p class="text-xs mt-1 {{ $balanceDue <= 0 ? 'text-success-600' : 'text-danger-600' }}">{{ $paymentStatus }}</p>
        </x-filament::section>
    </div>

    <div class="mb-2 flex items-center gap-4">
        @php
        $passportUrl = $this->record->avatar_url
            ? asset('uploads/' . ltrim($this->record->avatar_url, '/'))
            : null;
        @endphp
        @if ($passportUrl)
            <img src="{{ $passportUrl }}" alt="Passport"
                 class="w-16 h-16 rounded-full object-cover border-2 border-primary-500 shadow">
        @else
            <div class="w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center border-2 border-gray-300 dark:border-gray-600">
                <x-heroicon-o-user class="w-8 h-8 text-gray-400"/>
            </div>
        @endif
        <div>
            <div class="text-base font-semibold text-gray-900 dark:text-white">
                {{ $this->record->first_name }} {{ $this->record->last_name }}
                <span class="ml-2 text-xs font-normal text-gray-500 dark:text-gray-400">{{ $this->record->student_number }}</span>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Select a section to view or edit student details.</div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-4">

        {{-- IDENTITY & PASSPORT --}}
        <a href="{{ \App\Filament\Resources\StudentResource\Pages\EditStudentIdentity::getUrl(['record' => $this->record->getRouteKey()]) }}"
           class="group flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm hover:shadow-md hover:border-primary-400 dark:hover:border-primary-500 transition-all">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center">
                <x-heroicon-o-user class="w-7 h-7 text-primary-600 dark:text-primary-400"/>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-900 dark:text-white text-base">IDENTITY & PASSPORT</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Course, matric number, start date & passport photo</div>
            </div>
            <x-heroicon-o-chevron-right class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-primary-500 transition-colors"/>
        </a>

        {{-- CORE INFORMATION --}}
        <a href="{{ \App\Filament\Resources\StudentResource\Pages\EditStudentCore::getUrl(['record' => $this->record->getRouteKey()]) }}"
           class="group flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm hover:shadow-md hover:border-primary-400 dark:hover:border-primary-500 transition-all">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                <x-heroicon-o-identification class="w-7 h-7 text-blue-600 dark:text-blue-400"/>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-900 dark:text-white text-base">CORE INFORMATION</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Name, phone, branch, address & guardians</div>
            </div>
            <x-heroicon-o-chevron-right class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors"/>
        </a>

        {{-- ACADEMIC & PROGRESS --}}
        <a href="{{ \App\Filament\Resources\StudentResource\Pages\ViewStudentAcademic::getUrl(['record' => $this->record->getRouteKey()]) }}"
           class="group flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm hover:shadow-md hover:border-primary-400 dark:hover:border-primary-500 transition-all">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-green-50 dark:bg-green-900/30 flex items-center justify-center">
                <x-heroicon-o-academic-cap class="w-7 h-7 text-green-600 dark:text-green-400"/>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-900 dark:text-white text-base">ACADEMIC & PROGRESS</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Assessment stats per quarterly intake</div>
            </div>
            <x-heroicon-o-chevron-right class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-green-500 transition-colors"/>
        </a>

        {{-- DOCUMENT VAULT --}}
        <a href="{{ \App\Filament\Resources\StudentResource\Pages\EditStudentDocuments::getUrl(['record' => $this->record->getRouteKey()]) }}"
           class="group flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm hover:shadow-md hover:border-primary-400 dark:hover:border-primary-500 transition-all">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-yellow-50 dark:bg-yellow-900/30 flex items-center justify-center">
                <x-heroicon-o-folder-open class="w-7 h-7 text-yellow-600 dark:text-yellow-400"/>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-900 dark:text-white text-base">DOCUMENT VAULT</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">JAMB, WAEC, NECO & birth certificate</div>
            </div>
            <x-heroicon-o-chevron-right class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-yellow-500 transition-colors"/>
        </a>

    </div>

    @if(in_array(auth()->user()?->role, ['super_admin', 'admin', 'accounts_clerk'], true))
        <x-filament::section>
            <h3 class="text-sm font-semibold mb-2">Payments</h3>
            @php
                $payments = $this->record->payments()->latest()->get();
            @endphp
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th class="text-left p-2">Payment ID</th>
                            <th class="text-left p-2">Receipt No.</th>
                            <th class="text-left p-2">Date</th>
                            <th class="text-left p-2">Amount</th>
                            <th class="text-left p-2">Status</th>
                            <th class="text-left p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr class="border-t">
                                <td class="p-2">{{ $payment->payment_number }}</td>
                                <td class="p-2">{{ $payment->receipt_number ?: 'Pending' }}</td>
                                <td class="p-2">{{ optional($payment->processed_at ?: $payment->payment_date)?->format('Y-m-d H:i') }}</td>
                                <td class="p-2">₦{{ number_format((float) ($payment->amount_paid ?: $payment->amount), 2) }}</td>
                                <td class="p-2">{{ strtoupper((string) ($payment->status ?? $payment->payment_status)) }}</td>
                                <td class="p-2 space-x-2">
                                    @if(($payment->status ?? null) === 'success')
                                        <a href="{{ route('portal.payments.receipt', $payment) }}" class="text-primary-600 hover:underline" target="_blank">Download Receipt</a>
                                    @endif
                                    @if($payment->receipt_evidence_path)
                                        <a href="{{ asset('uploads/' . ltrim($payment->receipt_evidence_path, '/')) }}" class="text-blue-600 hover:underline font-medium" target="_blank">View Evidence</a>
                                    @endif
                                    <a href="{{ \App\Filament\Resources\PaymentResource\Pages\EditPayment::getUrl(['record' => $payment->id]) }}" class="text-gray-600 hover:underline">View Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-2 text-gray-500">No payments yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    @endif

</x-filament-panels::page>