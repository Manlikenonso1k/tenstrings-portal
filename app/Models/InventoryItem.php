<?php

namespace App\Models;

use App\Enums\ItemCondition;
use App\Enums\ItemStatus;
use App\Models\Concerns\ScopedToBranch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InventoryItem extends Model
{
    use LogsActivity;
    use ScopedToBranch;
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'inventory_room_id',
        'inventory_category_id',
        'name',
        'asset_tag',
        'serial_number',
        'brand',
        'model',
        'quantity',
        'unit',
        'condition',
        'status',
        'acquisition_date',
        'purchase_cost',
        'supplier',
        'photo_path',
        'notes',
        'last_verified_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'condition' => ItemCondition::class,
        'status' => ItemStatus::class,
        'quantity' => 'integer',
        'acquisition_date' => 'date',
        'purchase_cost' => 'decimal:2',
        'last_verified_at' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(InventoryRoom::class, 'inventory_room_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(InventoryCategory::class, 'inventory_category_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class)->latest('moved_at');
    }

    public function transfers(): HasMany
    {
        return $this->hasMany(InventoryTransfer::class, 'item_id')->latest('date');
    }

    public function auditLines(): HasMany
    {
        return $this->hasMany(InventoryAuditLine::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Items the CEO needs to look at: poor/damaged/needs_repair condition, or
     * under_repair/missing status.
     */
    public function scopeNeedsAttention(Builder $query): Builder
    {
        return $query->where(function (Builder $q): void {
            $q->whereIn('condition', ItemCondition::needingAttention())
                ->orWhereIn('status', ItemStatus::needingAttention());
        });
    }

    public function scopeNotVerifiedSince(Builder $query, \DateTimeInterface $since): Builder
    {
        return $query->where(function (Builder $q) use ($since): void {
            $q->whereNull('last_verified_at')->orWhere('last_verified_at', '<', $since);
        });
    }

    public function needsAttention(): bool
    {
        return in_array($this->condition?->value, ItemCondition::needingAttention(), true)
            || in_array($this->status?->value, ItemStatus::needingAttention(), true);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('inventory_items')
            ->logOnly([
                'branch_id',
                'inventory_room_id',
                'inventory_category_id',
                'name',
                'asset_tag',
                'serial_number',
                'brand',
                'model',
                'quantity',
                'condition',
                'status',
                'purchase_cost',
                'last_verified_at',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
