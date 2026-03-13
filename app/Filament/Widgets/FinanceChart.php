<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class FinanceChart extends ChartWidget
{
    protected static ?string $heading = 'Finance Overview (Revenue vs Pending)';

    protected static ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = null;

    private ?array $cachedTotals = null;

    public function mount(): void
    {
        $this->filter = now()->year . '_q1';
    }

    protected function getType(): string
    {
        return 'bar';
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
                    'label' => 'Amount (NGN)',
                    'data' => [
                        $totals['revenue'],
                        $totals['pending'],
                    ],
                    'backgroundColor' => [
                        '#2563eb',
                        '#dc2626',
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

    public function getDescription(): string | Htmlable | null
    {
        $totals = $this->resolveTotals();

        return 'Total NGN: Revenue ' . $this->formatNaira($totals['revenue'])
            . ' | Pending ' . $this->formatNaira($totals['pending']);
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
            ->selectRaw('COALESCE(SUM(fees_paid), 0) as revenue, COALESCE(SUM(balance_due), 0) as pending')
            ->first();

        return $this->cachedTotals = [
            'revenue' => (float) ($totals->revenue ?? 0),
            'pending' => (float) ($totals->pending ?? 0),
        ];
    }

    private function formatNaira(float $amount): string
    {
        return 'NGN ' . number_format($amount, 2);
    }
}
