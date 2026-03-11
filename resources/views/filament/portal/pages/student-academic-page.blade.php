<x-filament-panels::page>
    <div class="mb-5">
        <a href="{{ \App\Filament\Portal\Pages\StudentDataPage::getUrl() }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
            <x-heroicon-o-arrow-left class="w-4 h-4"/>
            Back to Student Data Hub
        </a>
    </div>

    @php $payload = $this->getAcademicStats(); $student = $payload['student'] ?? null; @endphp

    <x-filament::section icon="heroicon-o-academic-cap" heading="ACADEMIC & PROGRESS">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach (['FEBRUARY', 'MAY', 'AUGUST', 'NOVEMBER'] as $month)
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                    <div class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-1">{{ $month }}</div>
                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $payload['stats'][$month] ?? '-' }}
                    </div>
                </div>
            @endforeach
        </div>

        @if ($student)
            <div class="flex flex-wrap gap-3 mt-6">
                <a href="{{ route('students.print.admission_letter', $student) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition-colors">
                    <x-heroicon-o-document-text class="w-4 h-4"/>
                    Print Admission Letter
                </a>
                <a href="{{ route('students.print.biodata', $student) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium transition-colors">
                    <x-heroicon-o-user-circle class="w-4 h-4"/>
                    Print Bio-data
                </a>
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>
