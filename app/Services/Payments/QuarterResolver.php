<?php

namespace App\Services\Payments;

use Carbon\CarbonImmutable;

class QuarterResolver
{
    public function currentQuarter(?CarbonImmutable $date = null): string
    {
        $date ??= CarbonImmutable::now();
        $year = (int) $date->year;
        $month = (int) $date->month;

        return match (true) {
            $month >= 2 && $month <= 4 => 'Q1-' . $year,
            $month >= 5 && $month <= 7 => 'Q2-' . $year,
            $month >= 8 && $month <= 10 => 'Q3-' . $year,
            $month >= 11 => 'Q4-' . $year,
            default => 'Q4-' . ($year - 1),
        };
    }

    public function futureQuarter(int $offset = 1, ?CarbonImmutable $date = null): string
    {
        $date ??= CarbonImmutable::now();
        $quarterStartMonth = $this->quarterStartMonth((int) $date->month);
        $startDate = CarbonImmutable::create((int) $date->year, $quarterStartMonth, 1);

        $target = $startDate->addMonths($offset * 3);
        $targetYear = (int) $target->year;

        return match ((int) $target->month) {
            2 => 'Q1-' . $targetYear,
            5 => 'Q2-' . $targetYear,
            8 => 'Q3-' . $targetYear,
            11 => 'Q4-' . $targetYear,
            default => 'Q1-' . $targetYear,
        };
    }

    private function quarterStartMonth(int $month): int
    {
        return match (true) {
            $month >= 2 && $month <= 4 => 2,
            $month >= 5 && $month <= 7 => 5,
            $month >= 8 && $month <= 10 => 8,
            $month >= 11 => 11,
            default => 11,
        };
    }
}
