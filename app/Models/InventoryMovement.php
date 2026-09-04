<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    protected $fillable = [
        'inventory_item_id',
        'from_room_id',
        'to_room_id',
        'from_branch_id',
        'to_branch_id',
        'quantity',
        'reason',
        'moved_by',
        'moved_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'moved_at' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function fromRoom(): BelongsTo
    {
        return $this->belongsTo(InventoryRoom::class, 'from_room_id')->withoutGlobalScope('branch');
    }

    public function toRoom(): BelongsTo
    {
        return $this->belongsTo(InventoryRoom::class, 'to_room_id')->withoutGlobalScope('branch');
    }

    public function fromBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    public function toBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    public function mover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moved_by');
    }
}
