<?php

namespace App\Support;

class GradeCalculator
{
    public static function percentage(float $score, float $maximum): float
    {
        if ($maximum <= 0) {
            return 0;
        }

        return round(($score / $maximum) * 100, 2);
    }

    public static function letter(float $percentage): string
    {
        return match (true) {
            $percentage >= 70 => 'A',
            $percentage >= 60 => 'B',
            $percentage >= 50 => 'C',
            $percentage >= 45 => 'D',
            $percentage >= 40 => 'E',
            default => 'F',
        };
    }
}
