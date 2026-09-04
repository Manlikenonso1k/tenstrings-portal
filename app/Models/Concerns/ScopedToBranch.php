<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * Restricts a model's queries to the acting user's branch unless they hold
 * `inventory.view_all_branches`.
 *
 * Applied to InventoryRoom, InventoryItem and InventoryAudit. Unauthenticated
 * contexts (console commands, seeders, queued jobs) are left unscoped — there
 * is no user to scope to, and scoping them to nothing would silently break
 * migrations and reports.
 */
trait ScopedToBranch
{
    protected static function bootScopedToBranch(): void
    {
        static::addGlobalScope('branch', function (Builder $query): void {
            $user = Auth::user();

            if (! $user) {
                return;
            }

            if ($user->can('inventory.view_all_branches')) {
                return;
            }

            $query->where($query->getModel()->getTable() . '.branch_id', $user->branch_id);
        });
    }

    /**
     * Escape hatch for the rare place that legitimately needs every branch —
     * e.g. resolving a transfer target. Use sparingly and never in a listing
     * the officer can see.
     */
    public static function acrossAllBranches(): Builder
    {
        return static::query()->withoutGlobalScope('branch');
    }
}
