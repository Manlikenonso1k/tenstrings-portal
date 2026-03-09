<?php

namespace App\Filament\Portal\Pages;

use App\Models\Grade;
use Filament\Pages\Page;

class ResultsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'RESULTS';

    protected static string $view = 'filament.portal.pages.results-page';

    protected function getViewData(): array
    {
        $studentId = auth()->user()?->student?->id;

        return [
            'results' => Grade::query()
                ->where('student_id', $studentId)
                ->latest('date_recorded')
                ->get(),
        ];
    }
}
