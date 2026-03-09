<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_number',
        'student_id',
        'course_id',
        'issue_date',
        'file_path',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Certificate $certificate) {
            if (! $certificate->certificate_number) {
                $nextId = static::max('id') + 1;
                $certificate->certificate_number = 'CERT-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
            }
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
