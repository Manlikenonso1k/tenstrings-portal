<?php

namespace App\Support;

use App\Models\Branch;
use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use Illuminate\Support\Facades\DB;

/**
 * Generates asset tags of the form TS-{BRANCH_CODE}-{CATEGORY_ABBR}-{0001},
 * e.g. TS-IKJ-ITE-0042.
 *
 * The sequence is per branch + category and is drawn from a counter row that
 * is locked for update inside a transaction, so two officers saving at the
 * same moment cannot collide.
 */
class AssetTagGenerator
{
    public static function generate(int $branchId, int $categoryId): string
    {
        $branchCode = self::branchCode($branchId);
        $categoryAbbr = self::categoryAbbreviation($categoryId);
        $prefix = (string) config('inventory.asset_tag_prefix', 'TS');

        // A manually entered tag can already occupy a slot, so keep drawing
        // until we land on one nothing else holds.
        for ($attempt = 0; $attempt < 100; $attempt++) {
            $sequence = self::nextSequence($branchId, $categoryId);
            $tag = sprintf('%s-%s-%s-%04d', $prefix, $branchCode, $categoryAbbr, $sequence);

            $taken = InventoryItem::query()
                ->withoutGlobalScope('branch')
                ->withTrashed()
                ->where('asset_tag', $tag)
                ->exists();

            if (! $taken) {
                return $tag;
            }
        }

        throw new \RuntimeException("Could not allocate an asset tag for branch {$branchId} / category {$categoryId}.");
    }

    /**
     * Claim the next sequence number for this branch + category pair.
     */
    protected static function nextSequence(int $branchId, int $categoryId): int
    {
        return DB::transaction(function () use ($branchId, $categoryId): int {
            $counter = DB::table('inventory_asset_tag_counters')
                ->where('branch_id', $branchId)
                ->where('inventory_category_id', $categoryId)
                ->lockForUpdate()
                ->first();

            if ($counter === null) {
                // Another writer may win the race to insert; fall back to
                // re-reading under the lock rather than failing the save.
                try {
                    DB::table('inventory_asset_tag_counters')->insert([
                        'branch_id' => $branchId,
                        'inventory_category_id' => $categoryId,
                        'next_sequence' => 2,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    return 1;
                } catch (\Illuminate\Database\QueryException) {
                    $counter = DB::table('inventory_asset_tag_counters')
                        ->where('branch_id', $branchId)
                        ->where('inventory_category_id', $categoryId)
                        ->lockForUpdate()
                        ->first();
                }
            }

            $sequence = (int) $counter->next_sequence;

            DB::table('inventory_asset_tag_counters')
                ->where('id', $counter->id)
                ->update([
                    'next_sequence' => $sequence + 1,
                    'updated_at' => now(),
                ]);

            return $sequence;
        }, 5);
    }

    protected static function branchCode(int $branchId): string
    {
        $branch = Branch::query()->find($branchId);

        $code = $branch?->code;

        if (blank($code)) {
            $code = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', (string) $branch?->name) ?: 'BRN', 0, 3));
        }

        return strtoupper((string) $code);
    }

    protected static function categoryAbbreviation(int $categoryId): string
    {
        $category = InventoryCategory::query()->find($categoryId);

        return $category?->tagAbbreviation() ?? 'GEN';
    }
}
