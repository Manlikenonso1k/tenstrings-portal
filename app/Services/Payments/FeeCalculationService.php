<?php

namespace App\Services\Payments;

use App\Models\Course;

class FeeCalculationService
{
    /**
     * Calculate the required payment amount based on course duration.
     *
     * Rules:
     * - If course duration < 12 months: Required Amount = 100% of course fee
     * - If course duration >= 12 months: Required Amount = 70% of course fee (minimum deposit)
     */
    public function calculateRequiredAmount(Course $course, float $totalCourseFee): float
    {
        $durationMonths = (int) ($course->duration_months ?? 0);

        if ($durationMonths < 12) {
            // Short course: 100% of course fee
            return round($totalCourseFee, 2);
        }

        // Long course: 70% of course fee as minimum deposit
        return round($totalCourseFee * 0.7, 2);
    }

    /**
     * Get the payment percentage based on course duration.
     */
    public function getRequiredPercentage(Course $course): int
    {
        $durationMonths = (int) ($course->duration_months ?? 0);

        return $durationMonths < 12 ? 100 : 70;
    }
}
