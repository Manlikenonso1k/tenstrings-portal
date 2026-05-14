<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;

class EnrollmentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'accounts_clerk', 'instructor', 'student'], true);
    }

    public function view(User $user, Enrollment $enrollment): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'accounts_clerk', 'instructor', 'student'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin'], true);
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        return in_array($user->role, ['super_admin', 'admin'], true);
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
        return in_array($user->role, ['super_admin', 'admin'], true);
    }
}