<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class BranchFinanceChart extends ChartWidget
{
    protected static ?string $heading = 'Branch Revenue vs Pending';

    protected static ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = null;

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
        [$year, $quarter] = $this->parseFilter();

        $rows = $this->buildBaseQuery($year, $quarter)
            ->selectRaw("
                COALESCE(NULLIF(branch, ''), 'Legacy/Unassigned') as branch_name,
                COALESCE(SUM(fees_paid), 0) as revenue,
                COALESCE(SUM(balance_due), 0) as pending
            ")
            ->groupByRaw("COALESCE(NULLIF(branch, ''), 'Legacy/Unassigned')")
            ->orderByRaw('revenue DESC')
            ->get();

        $labels        = $rows->pluck('branch_name')->toArray();
        $revenues      = $rows->pluck('revenue')->map(fn ($v) => (float) $v)->toArray();
        $pendingValues = $rows->pluck('pending')->map(fn ($v) => (float) $v)->toArray();

        $revColors = $rows->map(fn ($r) => $r->branch_name === 'Legacy/Unassigned' ? '#9ca3af' : '#2563eb')->toArray();
        $penColors = $rows->map(fn ($r) => $r->branch_name === 'Legacy/Unassigned' ? '#6b7280' : '#dc2626')->toArray();

        return [
            'datasets' => [
                [
                    'label'           => 'Revenue (NGN)',
                    'data'            => $revenues,
                    'backgroundColor' => $revColors,
                    'borderColor'     => $revColors,
                    'borderWidth'     => 1,
                    'borderRadius'    => 6,
                ],
                [
                    'label'           => 'Pending (NGN)',
                    'data'            => $pendingValues,
                    'backgroundColor' => $penColors,
                    'borderColor'     => $penColors,
                    'borderWidth'     => 1,
                    'borderRadius'    => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive'          => true,
            'maintainAspectRatio' => false,
            'animation'           => [
                'duration' => 900,
                'easing'   => 'easeOutQuart',
            ],
            'plugins' => [
                'legend' => ['position' => 'top'],
            ],
            'scales' => [
                'x' => ['stacked' => false],
                'y' => ['beginAtZero' => true],
            ],
        ];
    }

    private function parseFilter(): array
    {
        $raw   = $this->filter ?? (now()->year . '_q1');
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
            'q1'    => [2, 3, 4],
            'q2'    => [5, 6, 7],
            'q3'    => [8, 9, 10],
            default => [2, 3, 4],
        };

        return Student::query()
            ->whereYear('start_date', $year)
            ->whereRaw('MONTH(start_date) IN (' . implode(',', $months) . ')');
    }
}
