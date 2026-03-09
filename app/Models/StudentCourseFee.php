<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCourseFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'total_course_fee',
        'amount_paid',
        'outstanding_balance',
        'payment_plan',
        'due_date',
        'status',
    ];

    protected $casts = [
        'total_course_fee' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
