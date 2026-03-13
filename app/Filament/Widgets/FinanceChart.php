<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class FinanceChart extends ChartWidget
{
    protected static ?string $heading = 'Finance Overview (Revenue vs Pending)';

    protected static ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = 'q1';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return [
            'q1' => 'Q1: February - April',
            'q2' => 'Q2: May - July',
            'q3' => 'Q3: August - October',
            'q4' => 'Q4: November - January',
        ];
    }

    protected function getData(): array
    {
        $months = $this->monthsForFilter();

        $query = Student::query()
            ->when($months !== [], fn (Builder $q) => $q->whereRaw('MONTH(start_date) IN (' . implode(',', $months) . ')'));

        $totals = $query->selectRaw('COALESCE(SUM(fees_paid), 0) as revenue, COALESCE(SUM(balance_due), 0) as pending')->first();

        return [
            'datasets' => [
                [
                    'label' => 'Amount (NGN)',
                    'data' => [
                        (float) ($totals->revenue ?? 0),
                        (float) ($totals->pending ?? 0),
                    ],
                    'backgroundColor' => [
                        '#2563eb', // Tenstrings blue (revenue)
                        '#dc2626', // warning red (pending)
                    ],
                    'borderColor' => [
                        '#1d4ed8',
                        '#b91c1c',
                    ],
                    'borderWidth' => 1,
                    'borderRadius' => 10,
                ],
            ],
            'labels' => ['Revenue', 'Pending'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'animation' => [
                'duration' => 900,
                'easing' => 'easeOutQuart',
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    /**
     * @return array<int>
     */
    private function monthsForFilter(): array
    {
        return match ($this->filter) {
            'q1' => [2, 3, 4],
            'q2' => [5, 6, 7],
            'q3' => [8, 9, 10],
            'q4' => [11, 12, 1],
            default => [2, 3, 4],
        };
    }
}
