<?php

namespace App\Policies;

use App\Enums\CheckoutStatus;
use App\Models\InventoryCheckout;
use App\Models\User;

class InventoryCheckoutPolicy
{
    use Concerns\ChecksBranchAccess;

    public function viewAny(User $user): bool
    {
        return $user->can('inventory_checkout.view');
    }

    public function view(User $user, InventoryCheckout $checkout): bool
    {
        return $user->can('inventory_checkout.view') && $this->sharesBranch($user, $checkout->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->can('inventory_checkout.create');
    }

    /**
     * Receiving items back. Only meaningful while something is still out.
     */
    public function return(User $user, InventoryCheckout $checkout): bool
    {
        return $user->can('inventory_checkout.return')
            && $checkout->status !== CheckoutStatus::Returned
            && $this->sharesBranch($user, $checkout->branch_id);
    }

    /**
     * Header details stay editable while the checkout is open; the evidence
     * lines never are.
     */
    public function update(User $user, InventoryCheckout $checkout): bool
    {
        return $user->can('inventory_checkout.create')
            && $checkout->status !== CheckoutStatus::Returned
            && $this->sharesBranch($user, $checkout->branch_id);
    }

    /**
     * The photo trail is evidence. Nobody below admin removes a checkout.
     */
    public function delete(User $user, InventoryCheckout $checkout): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Replacing or removing a confirmed photo is an admin-only act, and it is
     * written to the activity log by the caller.
     */
    public function managePhotos(User $user, InventoryCheckout $checkout): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }
}
