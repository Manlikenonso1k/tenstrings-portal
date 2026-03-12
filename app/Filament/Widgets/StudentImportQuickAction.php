<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudentImportQuickAction extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Student CSV Import', 'Upload and run import')
                ->description('Create students, generate passwords, and send emails')
                ->descriptionIcon('heroicon-m-arrow-up-tray')
                ->url(route('filament.admin.pages.import-students-csv'))
                ->color('primary'),
        ];
    }
}
