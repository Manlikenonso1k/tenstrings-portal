<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'student_id',
        'course_id',
        'amount_paid',
        'payment_date',
        'payment_method',
        'receipt_number',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Payment $payment) {
            if (! $payment->payment_number) {
                $nextId = static::max('id') + 1;
                $payment->payment_number = 'PAY-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
            }
        });

        static::created(function (Payment $payment) {
            if (! $payment->course_id) {
                return;
            }

            DB::transaction(function () use ($payment) {
                $fee = StudentCourseFee::query()
                    ->where('student_id', $payment->student_id)
                    ->where('course_id', $payment->course_id)
                    ->lockForUpdate()
                    ->first();

                if (! $fee) {
                    return;
                }

                $fee->amount_paid = (float) $fee->amount_paid + (float) $payment->amount_paid;
                $fee->outstanding_balance = max(0, (float) $fee->total_course_fee - (float) $fee->amount_paid);
                $fee->status = $fee->outstanding_balance <= 0 ? 'paid' : ((float) $fee->amount_paid > 0 ? 'partial' : 'pending');
                $fee->save();
            });
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
