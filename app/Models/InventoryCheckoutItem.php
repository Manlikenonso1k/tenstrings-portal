<?php

namespace App\Models;

use App\Enums\ItemCondition;
use App\Enums\PhotoStage;
use App\Enums\ReturnStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryCheckoutItem extends Model
{
    protected $fillable = [
        'inventory_checkout_id',
        'inventory_item_id',
        'quantity',
        'condition_out',
        'notes_out',
        'condition_in',
        'notes_in',
        'returned_quantity',
        'returned_at',
        'received_by',
        'return_status',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'returned_quantity' => 'integer',
        'condition_out' => ItemCondition::class,
        'condition_in' => ItemCondition::class,
        'return_status' => ReturnStatus::class,
        'returned_at' => 'datetime',
    ];

    public function checkout(): BelongsTo
    {
        return $this->belongsTo(InventoryCheckout::class, 'inventory_checkout_id');
    }

    public function item(): BelongsTo
    {
        // Read in the context of an already branch-scoped checkout.
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id')->withoutGlobalScope('branch');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(InventoryCheckoutPhoto::class);
    }

    public function photosOut(): HasMany
    {
        return $this->photos()->where('stage', PhotoStage::Out->value);
    }

    public function photosIn(): HasMany
    {
        return $this->photos()->where('stage', PhotoStage::In->value);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function outstandingQuantity(): int
    {
        return max(0, (int) $this->quantity - (int) $this->returned_quantity);
    }

    public function isFullyReturned(): bool
    {
        return $this->outstandingQuantity() === 0;
    }
}
