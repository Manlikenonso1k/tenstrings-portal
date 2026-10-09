<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class HostelPayment extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'student_id',
        'recorded_by',
        'payment_number',
        'amount',
        'payment_date',
        'payment_method',
        'status',
        'receipt_number',
        'receipt_evidence_path',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('hostel_payments')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $student = $this->student;

        if (! $student) {
            return;
        }

        $activity->properties = ($activity->properties ?? collect())
            ->put('student_name', $student->full_name)
            ->put('student_number', $student->student_number);
    }

    protected static function booted(): void
    {
        static::creating(function (HostelPayment $payment): void {
            if (! $payment->payment_number) {
                $nextId = static::max('id') + 1;
                $payment->payment_number = 'HST-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}