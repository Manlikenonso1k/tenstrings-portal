<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait ChecksBranchAccess
{
    /**
     * A user without `inventory.view_all_branches` may only touch records in
     * their own branch. The global scope already hides other branches from
     * listings; this closes the direct-URL hole.
     */
    protected function sharesBranch(User $user, ?int $branchId): bool
    {
        if (! in_array($user->role, ['inventory_officer', 'branch_manager'], true) && ! $user->hasAnyRole(['inventory_officer', 'branch_manager']) && $user->can('inventory.view_all_branches')) {
            return true;
        }

        return $branchId !== null && (int) $user->branch_id === (int) $branchId;
    }
}
