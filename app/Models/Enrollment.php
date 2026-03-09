<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_number',
        'student_id',
        'enrollment_date',
        'intake_month',
        'start_date',
        'expected_end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'start_date' => 'date',
        'expected_end_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Enrollment $enrollment) {
            if (! $enrollment->enrollment_number) {
                $nextId = static::max('id') + 1;
                $enrollment->enrollment_number = 'ENR-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollment_course');
    }
}
