<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'is_luxury_branch',
        'markup_percentage',
    ];

    protected $casts = [
        'is_luxury_branch'  => 'boolean',
        'markup_percentage' => 'decimal:2',
    ];

    /**
     * Calculate the effective course fee for this branch.
     *
     * If the branch is a luxury branch, the base fee is inflated
     * by the configured markup_percentage. Otherwise the raw
     * course_fee is returned unchanged.
     */
    public function effectiveFeeFor(Course $course): float
    {
        $base = (float) $course->course_fee;

        // Exclude Advanced Diploma courses from any luxury markup
        if (stripos($course->name, 'Advanced Diploma') !== false) {
            return $base;
        }

        if ($this->is_luxury_branch && $this->markup_percentage > 0) {
            return round($base * (1 + (float) $this->markup_percentage / 100), 2);
        }

        return $base;
    }

    /**
     * Fuzzy-match a raw student branch string (e.g. "AJAH BRANCH")
     * to a Branch record. Returns null if no match is found.
     */
    public static function findByStudentBranch(string $branchString): ?self
    {
        $needle = strtoupper(trim($branchString));

        if ($needle === '') {
            return null;
        }

        // Try exact match first
        $exact = static::query()->whereRaw('UPPER(name) = ?', [$needle])->first();
        if ($exact instanceof self) {
            return $exact;
        }

        // Fuzzy: look for any branch whose name is contained in the needle or vice-versa
        return static::all()->first(function (self $branch) use ($needle): bool {
            $name = strtoupper($branch->name);

            return str_contains($needle, $name)
                || str_contains($name, $needle)
                || str_contains($needle, explode(' ', $name)[0]); // e.g. "AJAH"
        });
    }
}
