<?php

namespace App\Support;

use App\Models\Enrollment;

class EnrollmentLimitService
{
    public static function canEnrollInCourses(int $studentId, array $selectedCourseIds, ?int $ignoreEnrollmentId = null): bool
    {
        $selected = collect($selectedCourseIds)->filter()->map(fn ($id) => (int) $id)->unique();

        if ($selected->count() > 2) {
            return false;
        }

        $existing = Enrollment::query()
            ->where('student_id', $studentId)
            ->where('status', 'ongoing')
            ->when($ignoreEnrollmentId, fn ($query) => $query->where('id', '!=', $ignoreEnrollmentId))
            ->with('courses:id')
            ->get()
            ->flatMap(fn (Enrollment $enrollment) => $enrollment->courses->pluck('id'))
            ->unique();

        return $existing->merge($selected)->unique()->count() <= 2;
    }
}
