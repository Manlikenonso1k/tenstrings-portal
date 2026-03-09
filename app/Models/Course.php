<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'duration_months',
        'duration_label',
        'course_fee',
        'description',
        'max_students_per_class',
        'is_active',
    ];

    protected $casts = [
        'course_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            if (! $course->code) {
                $nextId = static::max('id') + 1;
                $course->code = 'CRS-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'course_instructor');
    }

    public function enrollments()
    {
        return $this->belongsToMany(Enrollment::class, 'enrollment_course');
    }
}
