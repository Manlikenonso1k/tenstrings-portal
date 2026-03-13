<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\StudentResource;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudentImportQuickAction extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Student CSV Import', 'Open Students and import')
                ->description('Use the Import Students CSV action for live progress and summary')
                ->descriptionIcon('heroicon-m-arrow-up-tray')
                ->url(StudentResource::getUrl('index'))
                ->color('primary'),
        ];
    }
}
