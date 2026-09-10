<?php

namespace App\Models;

use App\Enums\PhotoStage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryCheckoutPhoto extends Model
{
    protected $fillable = [
        'inventory_checkout_item_id',
        'stage',
        'path',
        'uploaded_by',
    ];

    protected $casts = [
        'stage' => PhotoStage::class,
    ];

    public function line(): BelongsTo
    {
        return $this->belongsTo(InventoryCheckoutItem::class, 'inventory_checkout_item_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
