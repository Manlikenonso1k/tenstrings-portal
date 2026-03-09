<?php

namespace App\Models;

use App\Support\MatricNumberGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_number',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'address',
        'branch',
        'photo_path',
        'date_of_birth',
        'registration_date',
        'status',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'guardian_relationship',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'registration_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Student $student) {
            if (! $student->student_number) {
                $student->student_number = MatricNumberGenerator::generate($student->registration_date);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
