<?php

namespace App\Models;

use App\Enums\CheckoutStatus;
use App\Models\Concerns\ScopedToBranch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InventoryCheckout extends Model
{
    use LogsActivity;
    use ScopedToBranch;
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'reference',
        'event_name',
        'event_venue',
        'event_date',
        'responsible_person_name',
        'responsible_person_phone',
        'expected_return_at',
        'checked_out_by',
        'checked_out_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => CheckoutStatus::class,
        'event_date' => 'date',
        'expected_return_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(InventoryCheckoutItem::class);
    }

    public function releasedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_out_by');
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereIn('status', CheckoutStatus::open());
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereIn('status', [CheckoutStatus::Out->value, CheckoutStatus::PartiallyReturned->value])
            ->where('expected_return_at', '<', now());
    }

    public function isFullyReturned(): bool
    {
        return $this->lines->every(fn (InventoryCheckoutItem $line): bool => $line->isFullyReturned());
    }

    public function hasAnyReturn(): bool
    {
        return $this->lines->contains(fn (InventoryCheckoutItem $line): bool => $line->returned_quantity > 0);
    }

    /**
     * Recompute the header status from its lines. Overdue only survives while
     * something is still out.
     */
    public function refreshStatus(): void
    {
        $status = match (true) {
            $this->isFullyReturned() => CheckoutStatus::Returned,
            $this->hasAnyReturn() => CheckoutStatus::PartiallyReturned,
            default => CheckoutStatus::Out,
        };

        if ($status !== CheckoutStatus::Returned && $this->expected_return_at?->isPast()) {
            $status = CheckoutStatus::Overdue;
        }

        if ($this->status !== $status) {
            $this->forceFill(['status' => $status])->save();
        }
    }

    /**
     * Human readable, per year: CO-2026-0042.
     */
    public static function nextReference(): string
    {
        $year = now()->year;
        $prefix = "CO-{$year}-";

        $last = static::withoutGlobalScope('branch')
            ->withTrashed()
            ->where('reference', 'like', $prefix . '%')
            ->orderByDesc('reference')
            ->value('reference');

        $sequence = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

        return $prefix . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('inventory_checkouts')
            ->logOnly(['reference', 'branch_id', 'event_name', 'event_date', 'expected_return_at', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
