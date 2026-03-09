<?php

namespace App\Support;

use App\Models\CourseShortcodeMapping;

class CourseCatalog
{
    private const DEFAULTS = [
        'Advance Diploma in Music Performance - 18 months' => 'ADMP',
        'Advance Diploma in Music Production - 18 months' => 'ADPD',
        'Diploma in Music Performance - 1 year' => 'DMP',
        'Diploma in Music Production - 1 year' => 'DPD',
        'Certificate in Music Performance - 6 months' => 'CMP6',
        'Certificate in Music Production - 6 months' => 'CPD6',
        'Certificate in Gospel Music Performance - 3 months' => 'CGP3',
        'Certificate in Gospel Music Performance - 6 months' => 'CGP6',
        'Diploma in Gospel Music Performance - 1 year' => 'DGMP',
        'Certificate in Songwriting - 3/6 months' => 'CSW',
        'Certificate in Piano - 3/6 months' => 'CPN',
        'Certificate in Music Business - 3/6 months' => 'CMB',
        'Certificate in Guitar - 3/6 months' => 'CGT',
        'Certificate in Drums - 3/6 months' => 'CDR',
        'Certificate in Voice - 3/6 months' => 'CVO',
    ];

    public static function options(): array
    {
        return self::DEFAULTS;
    }

    public static function codeFor(string $courseName): string
    {
        $mapped = CourseShortcodeMapping::query()->where('course_name', $courseName)->value('short_code');

        return strtoupper((string) ($mapped ?: (self::DEFAULTS[$courseName] ?? 'GEN')));
    }
}
