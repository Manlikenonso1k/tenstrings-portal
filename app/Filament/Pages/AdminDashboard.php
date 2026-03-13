<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BranchEnrollmentDoughnut;
use App\Filament\Widgets\BranchFinanceChart;
use App\Filament\Widgets\FinanceChart;
use App\Filament\Widgets\FinanceRatioChart;
use Filament\Pages\Dashboard as BaseDashboard;

class AdminDashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            BranchFinanceChart::class,
            BranchEnrollmentDoughnut::class,
            FinanceChart::class,
            FinanceRatioChart::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StudentImportQuickAction::class,
            \App\Filament\Widgets\SchoolStatsOverview::class,
            \App\Filament\Widgets\BranchEnrollmentAnalytics::class,
            \App\Filament\Widgets\QuarterlyIntakeAnalytics::class,
            \App\Filament\Widgets\QuarterlyAssessmentAnalytics::class,
            \App\Filament\Widgets\RecentEnrollmentsTable::class,
            \Filament\Widgets\AccountWidget::class,
        ];
    }
}

