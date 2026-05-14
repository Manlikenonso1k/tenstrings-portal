<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'accounts_clerk', 'student'], true);
    }

    public function view(User $user, Payment $payment): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'accounts_clerk'], true)
            || ($user->isStudent() && $payment->student_id === $user->student?->id);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin'], true);
    }

    public function update(User $user, Payment $payment): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'accounts_clerk'], true);
    }

    public function delete(User $user, Payment $payment): bool
    {
        return in_array($user->role, ['super_admin', 'admin'], true);
    }
}