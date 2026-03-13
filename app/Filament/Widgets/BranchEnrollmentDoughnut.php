<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class BranchEnrollmentDoughnut extends ChartWidget
{
    protected static ?string $heading = 'Student Enrollment by Branch';

    protected static ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 2;

    public ?string $filter = null;

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
        [$year, $quarter] = $this->parseFilter();

        $rows = $this->buildBaseQuery($year, $quarter)
            ->selectRaw("
                COALESCE(NULLIF(branch, ''), 'Legacy/Unassigned') as branch_name,
                COUNT(*) as total
            ")
            ->groupBy('branch')
            ->orderByRaw('total DESC')
            ->get();

        $palette = ['#2563eb', '#16a34a', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#f97316', '#0891b2'];

        $colors = $rows->map(function ($row, $i) use ($palette) {
            return $row->branch_name === 'Legacy/Unassigned'
                ? '#9ca3af'
                : ($palette[$i % count($palette)]);
        })->toArray();

        return [
            'datasets' => [
                [
                    'data'            => $rows->pluck('total')->toArray(),
                    'backgroundColor' => $colors,
                    'borderColor'     => $colors,
                    'borderWidth'     => 1,
                ],
            ],
            'labels' => $rows->pluck('branch_name')->toArray(),
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
                'legend' => ['position' => 'bottom'],
            ],
            'cutout' => '55%',
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
