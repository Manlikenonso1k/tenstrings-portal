<?php

namespace App\Filament\Widgets;

use Filament\Actions\Imports\Models\Import;
use Filament\Widgets\Widget;

class ImportProgressWidget extends Widget
{
    protected static string $view = 'filament.widgets.import-progress-widget';

    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = '5s';

    protected function getViewData(): array
    {
        $import = Import::query()->latest('id')->first();

        if (! $import) {
            return [
                'hasImport' => false,
            ];
        }

        $totalRows = max(0, (int) $import->total_rows);
        $processedRows = min((int) $import->processed_rows, $totalRows > 0 ? $totalRows : (int) $import->processed_rows);
        $percentage = $totalRows > 0
            ? (int) min(100, round(($processedRows / $totalRows) * 100))
            : ($import->completed_at !== null ? 100 : 0);

        $failedRows = (int) $import->getFailedRowsCount();
        $isCompleted = $import->completed_at !== null;

        $status = match (true) {
            ! $isCompleted => 'Running',
            $failedRows > 0 => 'Completed with issues',
            default => 'Completed',
        };

        $failedRowsDownloadUrl = $failedRows > 0
            ? route('filament.imports.failed-rows.download', ['import' => $import])
            : null;

        return [
            'hasImport' => true,
            'fileName' => $import->file_name,
            'status' => $status,
            'processedRows' => $processedRows,
            'totalRows' => $totalRows,
            'successfulRows' => (int) $import->successful_rows,
            'failedRows' => $failedRows,
            'percentage' => $percentage,
            'failedRowsDownloadUrl' => $failedRowsDownloadUrl,
            'completedAt' => $import->completed_at,
        ];
    }
}
