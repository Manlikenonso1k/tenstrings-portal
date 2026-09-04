<?php

namespace App\Observers;

use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\InventoryRoom;
use App\Support\AssetTagGenerator;
use Illuminate\Support\Facades\Auth;

class InventoryItemObserver
{
    public function creating(InventoryItem $item): void
    {
        $this->syncBranchFromRoom($item);

        if (blank($item->created_by)) {
            $item->created_by = Auth::id();
        }

        // Manual override is allowed — some assets arrive with an existing
        // organisational tag.
        if (blank($item->asset_tag) && $item->branch_id && $item->inventory_category_id) {
            $item->asset_tag = AssetTagGenerator::generate(
                (int) $item->branch_id,
                (int) $item->inventory_category_id,
            );
        }
    }

    public function updating(InventoryItem $item): void
    {
        $this->syncBranchFromRoom($item);

        if (Auth::check()) {
            $item->updated_by = Auth::id();
        }
    }

    public function updated(InventoryItem $item): void
    {
        if (! $item->wasChanged('inventory_room_id') && ! $item->wasChanged('branch_id')) {
            return;
        }

        InventoryMovement::query()->create([
            'inventory_item_id' => $item->getKey(),
            'from_room_id' => $item->getOriginal('inventory_room_id'),
            'to_room_id' => $item->inventory_room_id,
            'from_branch_id' => $item->getOriginal('branch_id'),
            'to_branch_id' => $item->branch_id,
            'quantity' => (int) $item->quantity,
            'reason' => 'Location changed',
            'moved_by' => Auth::id(),
            'moved_at' => now(),
        ]);
    }

    /**
     * The room implies the branch, so keep the denormalised branch_id honest
     * whenever the room changes.
     */
    protected function syncBranchFromRoom(InventoryItem $item): void
    {
        if (blank($item->inventory_room_id)) {
            return;
        }

        if (! $item->isDirty('inventory_room_id') && filled($item->branch_id)) {
            return;
        }

        $room = InventoryRoom::query()
            ->withoutGlobalScope('branch')
            ->find($item->inventory_room_id);

        if ($room) {
            $item->branch_id = $room->branch_id;
        }
    }
}
