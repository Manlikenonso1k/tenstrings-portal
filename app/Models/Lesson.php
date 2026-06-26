<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';

    protected $fillable = [
        'module_id',
        'title',
        'type',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function completedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lesson_user')
            ->withPivot('completed_at')
            ->wherePivotNotNull('completed_at');
    }
}
