<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'accounts_clerk', 'instructor', 'student'], true);
    }

    public function view(User $user, Course $course): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'accounts_clerk', 'instructor', 'student'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin'], true);
    }

    public function update(User $user, Course $course): bool
    {
        return in_array($user->role, ['super_admin', 'admin'], true);
    }

    public function delete(User $user, Course $course): bool
    {
        return in_array($user->role, ['super_admin', 'admin'], true);
    }
}