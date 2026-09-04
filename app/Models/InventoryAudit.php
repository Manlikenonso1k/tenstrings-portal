<?php

namespace App\Models;

use App\Enums\AuditStatus;
use App\Models\Concerns\ScopedToBranch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryAudit extends Model
{
    use ScopedToBranch;

    protected $fillable = [
        'branch_id',
        'inventory_room_id',
        'title',
        'scheduled_for',
        'started_at',
        'completed_at',
        'status',
        'conducted_by',
        'notes',
    ];

    protected $casts = [
        'status' => AuditStatus::class,
        'scheduled_for' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(InventoryRoom::class, 'inventory_room_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(InventoryAuditLine::class);
    }

    public function conductor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conducted_by');
    }

    public function isEditable(): bool
    {
        return in_array($this->status, [AuditStatus::Draft, AuditStatus::InProgress], true);
    }

    /**
     * Lines where the count did not match, the item is missing, or the
     * condition found differs from what was on record.
     */
    public function variances(): \Illuminate\Support\Collection
    {
        return $this->lines
            ->filter(fn (InventoryAuditLine $line): bool => $line->hasVariance())
            ->values();
    }
}
