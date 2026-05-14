<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'accounts_clerk'], true);
    }

    public function view(User $user, Student $student): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'accounts_clerk'], true)
            || ($user->isStudent() && $user->student?->id === $student->id);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'accounts_clerk'], true);
    }

    public function update(User $user, Student $student): bool
    {
        return in_array($user->role, ['super_admin', 'admin'], true);
    }

    public function delete(User $user, Student $student): bool
    {
        return in_array($user->role, ['super_admin', 'admin'], true);
    }
}