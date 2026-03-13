<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SchoolStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $revenue = Student::query()->sum('fees_paid');
        $outstanding = Student::query()->sum('balance_due');

        return [
            Stat::make('Total Students', Student::query()->count()),
            Stat::make('Active Courses', Course::query()->where('is_active', true)->count()),
            Stat::make('Revenue', '₦' . number_format((float) $revenue, 2)),
            Stat::make('Pending Fees', '₦' . number_format((float) $outstanding, 2)),
        ];
    }
}
