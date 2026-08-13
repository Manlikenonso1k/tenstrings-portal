<?php

namespace App\Support;

use App\Models\Course;

class CourseCatalog
{
    /**
     * Legacy hardcoded courses kept as a fallback so that existing
     * students whose course names match these entries still resolve
     * correctly even if the database row is missing.
     */
    private const LEGACY_COURSES = [
        'Advanced Diploma in Music Performance' => [
            'code' => 'ADMP',
            'durations' => ['18 months'],
        ],
        'Advanced Diploma in Music Production' => [
            'code' => 'ADPD',
            'durations' => ['18 months'],
        ],
        'Diploma in Music Performance' => [
            'code' => 'DMP',
            'durations' => ['1 year'],
        ],
        'Diploma in Music Production' => [
            'code' => 'DPD',
            'durations' => ['1 year'],
        ],
        'Diploma in Gospel Music Performance' => [
            'code' => 'DGMP',
            'durations' => ['1 year'],
        ],
        'Certificate in Music Performance' => [
            'code' => 'CMP',
            'durations' => ['3 months', '6 months'],
        ],
        'Certificate in Music Production' => [
            'code' => 'CPD',
            'durations' => ['3 months', '6 months'],
        ],
        'Certificate in Gospel Music Performance' => [
            'code' => 'CGMP',
            'durations' => ['3 months', '6 months'],
        ],
        'Certificate in Songwriting' => [
            'code' => 'CSW',
            'durations' => ['3 months', '6 months'],
        ],
        'Certificate in Piano' => [
            'code' => 'CPN',
            'durations' => ['3 months', '6 months'],
        ],
        'Certificate in Music Business' => [
            'code' => 'CMB',
            'durations' => ['3 months', '6 months'],
        ],
        'Certificate in Guitar' => [
            'code' => 'CGTR',
            'durations' => ['3 months', '6 months'],
        ],
        'Certificate in Drums' => [
            'code' => 'CDRM',
            'durations' => ['3 months', '6 months'],
        ],
        'Certificate in Voice' => [
            'code' => 'CVOC',
            'durations' => ['3 months', '6 months'],
        ],
    ];

    /**
     * Build a merged catalog: database courses (active) take priority,
     * then legacy hardcoded entries fill in any gaps.
     */
    private static function allCourses(): array
    {
        $dbCourses = [];

        try {
            $courses = Course::query()
                ->where('is_active', true)
                ->get();

            foreach ($courses as $course) {
                $dbCourses[$course->name] = [
                    'code' => $course->code,
                    'durations' => [$course->duration_label],
                ];
            }
        } catch (\Throwable) {
            // Table may not exist yet (e.g. during migrations).
        }

        // Database entries win over legacy entries with the same name.
        return array_merge(self::LEGACY_COURSES, $dbCourses);
    }

    public static function options(): array
    {
        return collect(self::allCourses())
            ->mapWithKeys(fn (array $course, string $name): array => [$name => $course['code']])
            ->all();
    }

    public static function courseOptions(): array
    {
        $names = array_keys(self::allCourses());

        return array_combine($names, $names);
    }

    public static function durationOptionsFor(?string $courseName): array
    {
        $all = self::allCourses();

        if (! $courseName || ! isset($all[$courseName])) {
            return [];
        }

        $durations = $all[$courseName]['durations'];

        return array_combine($durations, $durations);
    }

    public static function defaultDurationFor(?string $courseName): ?string
    {
        $all = self::allCourses();

        if (! $courseName || ! isset($all[$courseName])) {
            return null;
        }

        $durations = $all[$courseName]['durations'];

        return count($durations) === 1 ? $durations[0] : null;
    }

    public static function hasSingleDuration(?string $courseName): bool
    {
        return self::defaultDurationFor($courseName) !== null;
    }

    public static function isValidDurationFor(?string $courseName, ?string $duration): bool
    {
        $all = self::allCourses();

        if (! $courseName || ! $duration || ! isset($all[$courseName])) {
            return false;
        }

        return in_array($duration, $all[$courseName]['durations'], true);
    }

    public static function codeFor(string $courseName): string
    {
        $all = self::allCourses();

        return strtoupper((string) ($all[$courseName]['code'] ?? 'GEN'));
    }
}

