<x-filament-widgets::widget>
    <x-filament::section>
        @if (! $hasImport)
            <div class="text-sm text-gray-600">
                No import has run yet.
            </div>
        @else
            <div class="space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Latest Student Import</p>
                        <p class="text-xs text-gray-600">{{ $fileName }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $status === 'Running' ? 'bg-info-50 text-info-700' : ($status === 'Completed' ? 'bg-success-50 text-success-700' : 'bg-warning-50 text-warning-700') }}">
                        {{ $status }}
                    </span>
                </div>

                <div>
                    <div class="mb-1 flex items-center justify-between text-xs text-gray-600">
                        <span>{{ number_format($processedRows) }} / {{ number_format($totalRows) }} processed</span>
                        <span>{{ $percentage }}%</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-gray-100">
                        <div
                            class="h-2 rounded-full {{ $status === 'Completed with issues' ? 'bg-warning-500' : 'bg-primary-600' }}"
                            style="width: {{ $percentage }}%"
                        ></div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-4 text-xs text-gray-600">
                    <span>Successful: {{ number_format($successfulRows) }}</span>
                    <span>Failed: {{ number_format($failedRows) }}</span>
                    @if ($failedRowsDownloadUrl)
                        <a href="{{ $failedRowsDownloadUrl }}" target="_blank" class="font-medium text-danger-600 hover:text-danger-700">
                            Download Failed Rows CSV
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
