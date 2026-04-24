<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAdvice extends Model
{
    use HasFactory;

    protected $table = 'payment_advices';

    protected $fillable = [
        'student_id',
        'course_id',
        'quarter_month',
        'year',
        'quarter_name',
        'amount',
        'status',
        'payment_id',
        'generated_at',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'generated_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
