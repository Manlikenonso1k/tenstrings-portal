<?php

namespace App\Observers;

use App\Models\InventoryRoom;
use Illuminate\Support\Str;

class InventoryRoomObserver
{
    public function saving(InventoryRoom $room): void
    {
        if (blank($room->code)) {
            $room->code = strtoupper(Str::slug((string) $room->name, '-'));
        }

        $room->code = strtoupper(trim((string) $room->code));
    }

    /**
     * A room's items follow the room to its new branch, so the denormalised
     * branch_id on every item stays truthful.
     */
    public function updated(InventoryRoom $room): void
    {
        if (! $room->wasChanged('branch_id')) {
            return;
        }

        $room->items()
            ->withoutGlobalScope('branch')
            ->update(['branch_id' => $room->branch_id]);
    }
}
