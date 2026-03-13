<?php

namespace App\Filament\Widgets;

use Filament\Actions\Imports\Models\Import;
use Filament\Widgets\Widget;

class ActiveImportTracker extends Widget
{
    protected static string $view = 'filament.widgets.active-import-tracker';

    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = '5s';

    protected function getViewData(): array
    {
        $import = Import::query()
            ->whereNull('completed_at')
            ->latest('id')
            ->first();

        if (! $import) {
            return [
                'hasActiveImport' => false,
            ];
        }

        $totalRows = max(1, (int) $import->total_rows);
        $processedRows = min((int) $import->processed_rows, $totalRows);
        $percentage = (int) min(100, round(($processedRows / $totalRows) * 100));

        return [
            'hasActiveImport' => true,
            'processedRows' => $processedRows,
            'totalRows' => $totalRows,
            'percentage' => $percentage,
        ];
    }
}
