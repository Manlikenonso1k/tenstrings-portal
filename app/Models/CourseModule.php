<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseModule extends Model
{
    use HasFactory;

    protected $table = 'modules';

    protected $fillable = [
        'course_id',
        'title',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'module_id')->orderBy('order');
    }

    public function previousModule(): ?self
    {
        return static::query()
            ->where('course_id', $this->course_id)
            ->where('order', '<', $this->order)
            ->orderByDesc('order')
            ->first();
    }

    public function isUnlockedFor(User $user): bool
    {
        if (! $user->isStudent()) {
            return true;
        }

        $previousModule = $this->previousModule();

        if (! $previousModule) {
            return true;
        }

        return $user->hasCompletedAllLessonsInModule($previousModule);
    }
}
