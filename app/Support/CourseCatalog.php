<?php

namespace App\Support;

class CourseCatalog
{
    private const COURSES = [
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

    public static function options(): array
    {
        return collect(self::COURSES)
            ->mapWithKeys(fn (array $course, string $name): array => [$name => $course['code']])
            ->all();
    }

    public static function courseOptions(): array
    {
        return array_combine(array_keys(self::COURSES), array_keys(self::COURSES));
    }

    public static function durationOptionsFor(?string $courseName): array
    {
        if (! $courseName || ! isset(self::COURSES[$courseName])) {
            return [];
        }

        $durations = self::COURSES[$courseName]['durations'];

        return array_combine($durations, $durations);
    }

    public static function defaultDurationFor(?string $courseName): ?string
    {
        if (! $courseName || ! isset(self::COURSES[$courseName])) {
            return null;
        }

        $durations = self::COURSES[$courseName]['durations'];

        return count($durations) === 1 ? $durations[0] : null;
    }

    public static function hasSingleDuration(?string $courseName): bool
    {
        return self::defaultDurationFor($courseName) !== null;
    }

    public static function isValidDurationFor(?string $courseName, ?string $duration): bool
    {
        if (! $courseName || ! $duration || ! isset(self::COURSES[$courseName])) {
            return false;
        }

        return in_array($duration, self::COURSES[$courseName]['durations'], true);
    }

    public static function codeFor(string $courseName): string
    {
        return strtoupper((string) (self::COURSES[$courseName]['code'] ?? 'GEN'));
    }
}
