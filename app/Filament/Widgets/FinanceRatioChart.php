<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class FinanceRatioChart extends ChartWidget
{
    protected static ?string $heading = 'Paid vs Unpaid Ratio';

    protected static ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 2;

    public ?string $filter = 'q1';

    protected function getType(): string
    {
        return 'doughnut';
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

        $totals = $query->selectRaw('COALESCE(SUM(fees_paid), 0) as paid, COALESCE(SUM(balance_due), 0) as unpaid')->first();

        return [
            'datasets' => [
                [
                    'data' => [
                        (float) ($totals->paid ?? 0),
                        (float) ($totals->unpaid ?? 0),
                    ],
                    'backgroundColor' => [
                        '#16a34a', // green (paid)
                        '#ef4444', // red (unpaid)
                    ],
                    'borderColor' => ['#15803d', '#dc2626'],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Paid', 'Unpaid'],
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
                    'position' => 'bottom',
                ],
            ],
            'cutout' => '62%',
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
