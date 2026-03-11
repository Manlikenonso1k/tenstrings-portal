<x-filament-panels::page>
    <x-filament::section icon="heroicon-o-academic-cap" heading="ACADEMIC & PROGRESS">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach (['FEBRUARY', 'MAY', 'AUGUST', 'NOVEMBER'] as $month)
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                    <div class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-1">{{ $month }}</div>
                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $this->getAcademicStats()[$month] ?? '-' }}
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-panels::page>
