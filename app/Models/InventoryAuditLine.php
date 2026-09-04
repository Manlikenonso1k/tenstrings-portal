<?php

namespace App\Models;

use App\Enums\ItemCondition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAuditLine extends Model
{
    protected $fillable = [
        'inventory_audit_id',
        'inventory_item_id',
        'expected_quantity',
        'counted_quantity',
        'condition_found',
        'is_missing',
        'remark',
    ];

    protected $casts = [
        'expected_quantity' => 'integer',
        'counted_quantity' => 'integer',
        'condition_found' => ItemCondition::class,
        'is_missing' => 'boolean',
    ];

    public function audit(): BelongsTo
    {
        return $this->belongsTo(InventoryAudit::class, 'inventory_audit_id');
    }

    public function item(): BelongsTo
    {
        // Lines are always read in the context of their audit, which is already
        // branch scoped — re-applying the item scope here would hide lines from
        // a branch manager mid-count if an item were moved.
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id')->withoutGlobalScope('branch');
    }

    public function hasVariance(): bool
    {
        if ($this->is_missing) {
            return true;
        }

        if ($this->counted_quantity === null) {
            return false;
        }

        return $this->counted_quantity !== $this->expected_quantity;
    }

    public function variance(): int
    {
        return (int) $this->counted_quantity - (int) $this->expected_quantity;
    }
}
