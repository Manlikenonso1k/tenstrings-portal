<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'payment_number',
        'user_id',
        'invoice_id',
        'student_id',
        'course_id',
        'gateway',
        'reference',
        'amount',
        'status',
        'gateway_response',
        'metadata',
        'processed_at',
        'amount_paid',
        'payment_date',
        'payment_method',
        'receipt_number',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'payment_date' => 'date',
        'processed_at' => 'datetime',
        'gateway_response' => 'array',
        'metadata' => 'array',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('payments')
            ->logFillable()
            ->logOnlyDirty();
    }
}
