<?php

namespace App\Support;

use App\Models\PortalSetting;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MatricNumberGenerator
{
    public static function generate(?string $registrationDate = null): string
    {
        return DB::transaction(function () use ($registrationDate) {
            $setting = PortalSetting::query()->lockForUpdate()->first();

            if (! $setting) {
                $setting = PortalSetting::query()->create([
                    'matric_pattern' => '{ycode}{seq:8}',
                    'next_sequence' => 1,
                ]);
            }

            $year = Carbon::parse($registrationDate ?? now())->year;
            $sequence = (int) $setting->next_sequence;

            do {
                $matric = self::applyPattern($setting->matric_pattern, $year, $sequence);
                $sequence++;
            } while (Student::query()->where('student_number', $matric)->exists());

            $setting->next_sequence = $sequence;
            $setting->save();

            return $matric;
        });
    }

    private static function applyPattern(string $pattern, int $year, int $sequence): string
    {
        $value = strtr($pattern, [
            '{yyyy}' => (string) $year,
            '{yy}' => substr((string) $year, -2),
            '{ycode}' => substr((string) $year, -2) . '0',
        ]);

        return (string) preg_replace_callback('/\{seq:(\d+)\}/', function (array $matches) use ($sequence) {
            $length = (int) $matches[1];

            return str_pad((string) $sequence, $length, '0', STR_PAD_LEFT);
        }, $value);
    }
}
