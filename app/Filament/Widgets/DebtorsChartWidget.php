<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;

class DebtorsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Debtors Breakdown (Paid vs Remaining)';

    protected static ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    // Use full width or let it adjust on the page
    // protected int|string|array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $totals = $this->resolveTotals();

        return [
            'datasets' => [
                [
                    'data' => [
                        $totals['paid'],
                        $totals['remaining'],
                    ],
                    'backgroundColor' => [
                        '#16a34a', // Green for Paid
                        '#dc2626', // Red for Remaining
                    ],
                    'borderColor' => [
                        '#15803d',
                        '#b91c1c',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Paid (NGN)', 'Remaining (NGN)'],
        ];
    }

    public function getDescription(): string | Htmlable | null
    {
        $totals = $this->resolveTotals();

        return 'Total Paid: ' . $this->formatNaira($totals['paid']) . ' | Total Remaining: ' . $this->formatNaira($totals['remaining']);
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
            'cutout' => '55%',
        ];
    }

    private function resolveTotals(): array
    {
        $totals = Student::query()
            ->where('balance_due', '>', 0)
            ->selectRaw('COALESCE(SUM(fees_paid), 0) as paid, COALESCE(SUM(balance_due), 0) as remaining')
            ->first();

        return [
            'paid' => (float) ($totals->paid ?? 0),
            'remaining' => (float) ($totals->remaining ?? 0),
        ];
    }

    private function formatNaira(float $amount): string
    {
        return 'NGN ' . number_format($amount, 2);
    }
}
