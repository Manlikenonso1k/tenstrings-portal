<?php

namespace App\Support;

use App\Models\Student;
use Carbon\Carbon;

class MatricNumberGenerator
{
    public static function generate(?string $startDate = null, ?string $courseCode = null): string
    {
        $date = Carbon::parse($startDate ?? now());
        $year = $date->format('Y');
        $month = $date->format('m');
        $code = strtoupper($courseCode ?: 'GEN');
        $prefix = $year . $month . $code;

        $sequence = Student::query()
            ->where('student_number', 'like', $prefix . '%')
            ->count() + 1;

        $matric = $prefix . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);

        while (Student::query()->where('student_number', $matric)->exists()) {
            $sequence++;
            $matric = $prefix . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
        }

        return $matric;
    }
}
