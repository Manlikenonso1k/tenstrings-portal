<?php

namespace App\Policies;

use App\Models\InventoryItem;
use App\Models\User;

class InventoryItemPolicy
{
    use Concerns\ChecksBranchAccess;

    public function viewAny(User $user): bool
    {
        return $user->can('item.view');
    }

    public function view(User $user, InventoryItem $item): bool
    {
        return $user->can('item.view') && $this->sharesBranch($user, $item->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->can('item.create');
    }

    public function update(User $user, InventoryItem $item): bool
    {
        return $user->can('item.update') && $this->sharesBranch($user, $item->branch_id);
    }

    public function delete(User $user, InventoryItem $item): bool
    {
        return $user->can('item.delete') && $this->sharesBranch($user, $item->branch_id);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('item.delete');
    }

    public function restore(User $user, InventoryItem $item): bool
    {
        return $this->delete($user, $item);
    }

    public function forceDelete(User $user, InventoryItem $item): bool
    {
        return $this->delete($user, $item);
    }

    public function transfer(User $user, InventoryItem $item): bool
    {
        return $user->can('item.transfer') && $this->sharesBranch($user, $item->branch_id);
    }

    public function dispose(User $user, InventoryItem $item): bool
    {
        return $user->can('item.dispose') && $this->sharesBranch($user, $item->branch_id);
    }

    public function viewCost(User $user): bool
    {
        return $user->can('inventory.view_costs');
    }
}
