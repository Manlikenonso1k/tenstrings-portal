<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'instructor_number',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'specialization',
        'photo_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Instructor $instructor) {
            if (! $instructor->instructor_number) {
                $nextId = static::max('id') + 1;
                $instructor->instructor_number = 'INS-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_instructor');
    }
}
