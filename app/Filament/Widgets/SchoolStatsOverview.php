<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentCourseFee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SchoolStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $revenue = Payment::query()->sum('amount_paid');
        $outstanding = StudentCourseFee::query()->sum('outstanding_balance');

        return [
            Stat::make('Total Students', Student::query()->count()),
            Stat::make('Active Courses', Course::query()->where('is_active', true)->count()),
            Stat::make('Revenue', '₦' . number_format((float) $revenue, 2)),
            Stat::make('Pending Fees', '₦' . number_format((float) $outstanding, 2)),
        ];
    }
}
