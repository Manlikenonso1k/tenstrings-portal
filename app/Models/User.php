<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar_path',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => in_array($this->role, ['super_admin', 'admin', 'accounts_clerk'], true),
            'portal' => $this->role === 'student',
            'instructor' => in_array($this->role, ['super_admin', 'instructor'], true),
            default => false,
        };
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $path = $this->student?->avatar_url;

        return $path ? asset('uploads/' . ltrim($path, '/')) : null;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin'], true);
    }

    public function isAccountsClerk(): bool
    {
        return $this->role === 'accounts_clerk';
    }

    public function isInstructor(): bool
    {
        return $this->role === 'instructor';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function instructor()
    {
        return $this->hasOne(Instructor::class);
    }

    public function loginSessions()
    {
        return $this->hasMany(LoginSession::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function completedLessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user')
            ->withPivot('completed_at')
            ->wherePivotNotNull('completed_at');
    }

    public function canAccessModule(CourseModule $module): bool
    {
        if (! $this->isStudent()) {
            return true;
        }

        return $module->isUnlockedFor($this);
    }

    public function canAccessLesson(Lesson $lesson): bool
    {
        return $this->canAccessModule($lesson->module);
    }

    public function hasCompletedAllLessonsInModule(CourseModule $module): bool
    {
        $lessonCount = $module->lessons()->count();

        if ($lessonCount === 0) {
            return true;
        }

        return $this->completedLessons()
            ->where('lessons.module_id', $module->getKey())
            ->distinct()
            ->count('lessons.id') >= $lessonCount;
    }

    public function unlockedModuleIdsForCourse(Course $course): array
    {
        $modules = $course->modules()
            ->with(['lessons:id,module_id'])
            ->orderBy('order')
            ->get(['id', 'course_id', 'title', 'order']);

        $completedLessonIds = $this->completedLessons()
            ->whereHas('module', function ($query) use ($course): void {
                $query->where('course_id', $course->getKey());
            })
            ->pluck('lessons.id')
            ->all();

        $completedLookup = array_fill_keys($completedLessonIds, true);
        $unlockedModuleIds = [];

        foreach ($modules as $index => $module) {
            if ($index === 0) {
                $unlockedModuleIds[] = $module->getKey();
                continue;
            }

            $previousModule = $modules[$index - 1];
            $previousLessonIds = $previousModule->lessons->pluck('id')->all();

            if ($previousLessonIds === []) {
                $unlockedModuleIds[] = $module->getKey();
                continue;
            }

            foreach ($previousLessonIds as $lessonId) {
                if (! isset($completedLookup[$lessonId])) {
                    continue 2;
                }
            }

            $unlockedModuleIds[] = $module->getKey();
        }

        return $unlockedModuleIds;
    }
}
