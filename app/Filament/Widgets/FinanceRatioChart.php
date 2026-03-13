<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class FinanceRatioChart extends ChartWidget
{
    protected static ?string $heading = 'Paid vs Unpaid Ratio';

    protected static ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 2;

    public ?string $filter = null;

    private ?array $cachedTotals = null;

    public function mount(): void
    {
        $this->filter = now()->year . '_q1';
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getFilters(): ?array
    {
        $current = (int) now()->year;
        $filters = [];
        foreach (range($current, $current - 2) as $year) {
            $filters["{$year}_q1"] = "{$year} · Q1: Feb – Apr";
            $filters["{$year}_q2"] = "{$year} · Q2: May – Jul";
            $filters["{$year}_q3"] = "{$year} · Q3: Aug – Oct";
            $filters["{$year}_q4"] = "{$year} · Q4: Nov – Jan";
        }

        return $filters;
    }

    protected function getData(): array
    {
        $totals = $this->resolveTotals();

        return [
            'datasets' => [
                [
                    'data' => [
                        $totals['paid'],
                        $totals['unpaid'],
                    ],
                    'backgroundColor' => [
                        '#16a34a',
                        '#ef4444',
                    ],
                    'borderColor' => ['#15803d', '#dc2626'],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Paid', 'Unpaid'],
        ];
    }

    public function getDescription(): string | Htmlable | null
    {
        $totals = $this->resolveTotals();

        return 'Total NGN: Paid ' . $this->formatNaira($totals['paid'])
            . ' | Unpaid ' . $this->formatNaira($totals['unpaid']);
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

    private function parseFilter(): array
    {
        $raw = $this->filter ?? (now()->year . '_q1');
        $parts = explode('_', $raw, 2);

        return [(int) ($parts[0] ?? now()->year), $parts[1] ?? 'q1'];
    }

    private function buildBaseQuery(int $year, string $quarter): Builder
    {
        if ($quarter === 'q4') {
            return Student::query()->where(function (Builder $q) use ($year) {
                $q->whereYear('start_date', $year)
                    ->whereRaw('MONTH(start_date) IN (11, 12)');
            })->orWhere(function (Builder $q) use ($year) {
                $q->whereYear('start_date', $year + 1)
                    ->whereRaw('MONTH(start_date) = 1');
            });
        }

        $months = match ($quarter) {
            'q1' => [2, 3, 4],
            'q2' => [5, 6, 7],
            'q3' => [8, 9, 10],
            default => [2, 3, 4],
        };

        return Student::query()
            ->whereYear('start_date', $year)
            ->whereRaw('MONTH(start_date) IN (' . implode(',', $months) . ')');
    }

    private function resolveTotals(): array
    {
        if ($this->cachedTotals !== null) {
            return $this->cachedTotals;
        }

        [$year, $quarter] = $this->parseFilter();

        $totals = $this->buildBaseQuery($year, $quarter)
            ->selectRaw('COALESCE(SUM(fees_paid), 0) as paid, COALESCE(SUM(balance_due), 0) as unpaid')
            ->first();

        return $this->cachedTotals = [
            'paid' => (float) ($totals->paid ?? 0),
            'unpaid' => (float) ($totals->unpaid ?? 0),
        ];
    }

    private function formatNaira(float $amount): string
    {
        return 'NGN ' . number_format($amount, 2);
    }
}
