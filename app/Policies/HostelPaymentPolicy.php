<?php

namespace App\Policies;

use App\Models\HostelPayment;
use App\Models\User;

class HostelPaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'accounts_clerk'], true);
    }

    public function view(User $user, HostelPayment $payment): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'accounts_clerk'], true);
    }

    public function update(User $user, HostelPayment $payment): bool
    {
        return in_array($user->role, ['super_admin', 'admin'], true);
    }

    public function delete(User $user, HostelPayment $payment): bool
    {
        return in_array($user->role, ['super_admin', 'admin'], true);
    }
}