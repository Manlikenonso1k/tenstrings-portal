<?php

namespace App\Policies;

use App\Models\InventoryRoom;
use App\Models\User;

class InventoryRoomPolicy
{
    use Concerns\ChecksBranchAccess;

    public function viewAny(User $user): bool
    {
        return $user->can('room.view');
    }

    public function view(User $user, InventoryRoom $room): bool
    {
        return $user->can('room.view') && $this->sharesBranch($user, $room->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->can('room.create');
    }

    public function update(User $user, InventoryRoom $room): bool
    {
        return $user->can('room.update') && $this->sharesBranch($user, $room->branch_id);
    }

    public function delete(User $user, InventoryRoom $room): bool
    {
        return $user->can('room.delete') && $this->sharesBranch($user, $room->branch_id);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('room.delete');
    }

    public function restore(User $user, InventoryRoom $room): bool
    {
        return $this->delete($user, $room);
    }

    public function forceDelete(User $user, InventoryRoom $room): bool
    {
        return $this->delete($user, $room);
    }
}
