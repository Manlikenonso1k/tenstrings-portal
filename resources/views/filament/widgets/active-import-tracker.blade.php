@if ($hasActiveImport)
    <x-filament-widgets::widget>
        <x-filament::section>
            <div class="space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex h-3 w-3 rounded-full bg-green-500"></span>
                        </span>
                        <p class="text-sm font-semibold text-gray-900">Live Import Progress</p>
                    </div>
                    <span class="text-xs font-medium text-gray-600">{{ $percentage }}%</span>
                </div>

                <p class="text-sm text-gray-700">
                    Importing Students: {{ number_format($processedRows) }} / {{ number_format($totalRows) }} rows processed ({{ $percentage }}%)
                </p>

                <div class="h-2 w-full rounded-full bg-gray-100">
                    <div class="h-2 rounded-full bg-primary-600 transition-all duration-500" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
        </x-filament::section>
    </x-filament-widgets::widget>
@endif
