<?php

namespace App\Models;

use App\Enums\RoomType;
use App\Models\Concerns\ScopedToBranch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InventoryRoom extends Model
{
    use LogsActivity;
    use ScopedToBranch;
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'name',
        'code',
        'floor',
        'room_type',
        'description',
        'is_active',
        'last_audited_at',
    ];

    protected $casts = [
        'room_type' => RoomType::class,
        'is_active' => 'boolean',
        'last_audited_at' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function audits(): HasMany
    {
        return $this->hasMany(InventoryAudit::class);
    }

    /**
     * True when the room has not been audited within the configured window
     * (or has never been audited at all).
     */
    public function isAuditStale(): bool
    {
        $days = (int) config('inventory.stale_audit_days', 90);

        return $this->last_audited_at === null
            || $this->last_audited_at->lt(now()->subDays($days));
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('inventory_rooms')
            ->logOnly([
                'branch_id',
                'name',
                'code',
                'floor',
                'room_type',
                'is_active',
                'last_audited_at',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
