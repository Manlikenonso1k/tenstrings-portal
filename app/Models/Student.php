<?php

namespace App\Models;

use App\Support\MatricNumberGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    public const MAIN_INTAKE_MONTHS = [2, 5, 8, 11];

    protected $fillable = [
        'user_id',
        'student_number',
        'selected_course_name',
        'selected_course_code',
        'duration',
        'fees_paid',
        'balance_due',
        'hostel_fee',
        'total_balance',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'address',
        'branch',
        'photo_path',
        'avatar_url',
        'birth_certificate_path',
        'jamb_path',
        'neco_path',
        'waec_path',
        'date_of_birth',
        'start_date',
        'registration_date',
        'status',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'guardian_relationship',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'start_date' => 'date',
        'registration_date' => 'date',
        'fees_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'hostel_fee' => 'decimal:2',
        'total_balance' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Student $student) {
            if (! $student->student_number) {
                $student->student_number = MatricNumberGenerator::generate(
                    $student->start_date ?? $student->registration_date,
                    $student->selected_course_code
                );
            }
        });

        static::deleting(function (Student $student): void {
            $student->user()?->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeMainIntakeMonths(Builder $query): Builder
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $padded = array_map(fn ($m) => str_pad((string) $m, 2, '0', STR_PAD_LEFT), self::MAIN_INTAKE_MONTHS);

            return $query
                ->whereNotNull('start_date')
                ->whereIn(DB::raw("strftime('%m', start_date)"), $padded);
        }

        return $query
            ->whereNotNull('start_date')
            ->whereIn(DB::raw('MONTH(start_date)'), self::MAIN_INTAKE_MONTHS);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
