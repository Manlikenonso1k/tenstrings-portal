<?php

namespace App\Filament\Widgets;

use App\Models\LoginSession;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class SystemActivityLineChart extends ChartWidget
{
    protected static ?string $heading = 'System Activity (Logins vs Imports)';

    protected static ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    private ?array $cachedSeries = null;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $series = $this->resolveSeries();

        return [
            'datasets' => [
                [
                    'label' => 'Logins',
                    'data' => $series['logins'],
                    'borderColor' => '#2563eb',
                    'backgroundColor' => 'rgba(37, 99, 235, 0.2)',
                    'pointBackgroundColor' => '#2563eb',
                    'pointRadius' => 4,
                    'tension' => 0.32,
                ],
                [
                    'label' => 'Imports',
                    'data' => $series['imports'],
                    'borderColor' => '#16a34a',
                    'backgroundColor' => 'rgba(22, 163, 74, 0.2)',
                    'pointBackgroundColor' => '#16a34a',
                    'pointRadius' => 4,
                    'tension' => 0.32,
                ],
            ],
            'labels' => $series['labels'],
        ];
    }

    public function getDescription(): string | Htmlable | null
    {
        $series = $this->resolveSeries();

        return '7-day totals: Logins ' . array_sum($series['logins'])
            . ' | Imports ' . array_sum($series['imports']);
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => ['position' => 'top'],
            ],
            'scales' => [
                'y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]],
            ],
        ];
    }

    public static function canView(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->isSuperAdmin();
    }

    private function resolveSeries(): array
    {
        if ($this->cachedSeries !== null) {
            return $this->cachedSeries;
        }

        $start = now()->startOfDay()->subDays(6);
        $days = collect(range(0, 6))->map(fn (int $offset) => $start->copy()->addDays($offset));

        $loginMap = LoginSession::query()
            ->where('login_at', '>=', $start)
            ->selectRaw('DATE(login_at) as day, COUNT(*) as total')
            ->groupByRaw('DATE(login_at)')
            ->pluck('total', 'day');

        $importMap = Activity::query()
            ->where('created_at', '>=', $start)
            ->where('event', 'imported_csv')
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'day');

        $labels = [];
        $logins = [];
        $imports = [];

        foreach ($days as $day) {
            $key = $day->toDateString();
            $labels[] = $day->format('D');
            $logins[] = (int) ($loginMap[$key] ?? 0);
            $imports[] = (int) ($importMap[$key] ?? 0);
        }

        return $this->cachedSeries = [
            'labels' => $labels,
            'logins' => $logins,
            'imports' => $imports,
        ];
    }
}
