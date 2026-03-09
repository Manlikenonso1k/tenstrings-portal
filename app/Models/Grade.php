<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'instructor_id',
        'assessment_type',
        'assessment_month',
        'score',
        'maximum_score',
        'percentage',
        'grade_letter',
        'date_recorded',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'maximum_score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'date_recorded' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
}
