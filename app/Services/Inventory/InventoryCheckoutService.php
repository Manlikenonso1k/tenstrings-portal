<?php

namespace App\Services\Inventory;

use App\Enums\CheckoutStatus;
use App\Enums\ItemCondition;
use App\Enums\ItemStatus;
use App\Enums\PhotoStage;
use App\Enums\ReturnStatus;
use App\Exceptions\InventoryCheckoutException;
use App\Models\InventoryCheckout;
use App\Models\InventoryCheckoutItem;
use App\Models\InventoryItem;
use App\Models\User;
use App\Notifications\InventoryItemReturnedBadly;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * All check-out and return logic lives here rather than in the Filament
 * actions, so the mobile API can call exactly the same rules later.
 *
 * The photo minimum is enforced here, not only in the form: a caller that
 * skips the wizard still cannot create evidence-free records.
 */
class InventoryCheckoutService
{
    public const MINIMUM_PHOTOS = 2;

    /**
     * @param  array{
     *     branch_id?: int|null,
     *     event_name: string,
     *     event_venue?: string|null,
     *     event_date: string|\DateTimeInterface,
     *     responsible_person_name: string,
     *     responsible_person_phone?: string|null,
     *     expected_return_at: string|\DateTimeInterface,
     *     notes?: string|null,
     *     lines: array<int, array{inventory_item_id: int, quantity: int, condition_out?: string|null, notes_out?: string|null, photos_out: array<int, string>}>
     * }  $data
     */
    public function checkout(array $data, User $actor): InventoryCheckout
    {
        $lines = array_values($data['lines'] ?? []);

        if ($lines === []) {
            throw InventoryCheckoutException::noLines();
        }

        return DB::transaction(function () use ($data, $lines, $actor): InventoryCheckout {
            $itemIds = array_map(static fn (array $line): int => (int) $line['inventory_item_id'], $lines);

            // Lock every item for the life of the transaction so two people
            // cannot both claim the last unit.
            $items = InventoryItem::query()
                ->withoutGlobalScope('branch')
                ->whereIn('id', $itemIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $branchId = (int) ($data['branch_id'] ?? $items->first()?->branch_id);

            $checkout = InventoryCheckout::query()->create([
                'branch_id' => $branchId,
                'reference' => InventoryCheckout::nextReference(),
                'event_name' => $data['event_name'],
                'event_venue' => $data['event_venue'] ?? null,
                'event_date' => $data['event_date'],
                'responsible_person_name' => $data['responsible_person_name'],
                'responsible_person_phone' => $data['responsible_person_phone'] ?? null,
                'expected_return_at' => $data['expected_return_at'],
                'checked_out_by' => $actor->getKey(),
                'checked_out_at' => now(),
                'status' => CheckoutStatus::Out,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($lines as $line) {
                $item = $items->get((int) $line['inventory_item_id']);

                if (! $item instanceof InventoryItem) {
                    throw new InventoryCheckoutException('One of the selected items no longer exists.');
                }

                $photos = array_values(array_filter($line['photos_out'] ?? []));
                $quantity = (int) ($line['quantity'] ?? 0);

                $this->assertPhotoMinimum($item->name, $photos);

                if ((int) $item->branch_id !== $branchId) {
                    throw InventoryCheckoutException::crossBranch((string) $item->name);
                }

                if ($item->isBlockedFromCheckout()) {
                    throw InventoryCheckoutException::itemBlocked(
                        (string) $item->name,
                        $item->status?->label() ?? (string) $item->status?->value,
                    );
                }

                // Recomputed inside the lock, so a concurrent checkout that
                // committed first is already reflected here.
                $available = $item->availableQuantity();

                if ($quantity < 1 || $quantity > $available) {
                    throw InventoryCheckoutException::notEnoughAvailable((string) $item->name, $quantity, $available);
                }

                $checkoutLine = $checkout->lines()->create([
                    'inventory_item_id' => $item->getKey(),
                    'quantity' => $quantity,
                    'condition_out' => $line['condition_out'] ?? $item->condition?->value ?? ItemCondition::Good->value,
                    'notes_out' => $line['notes_out'] ?? null,
                    'returned_quantity' => 0,
                ]);

                $this->storePhotos($checkoutLine, $photos, PhotoStage::Out, $actor);

                $this->markItemStatusAfterCheckout($item);
            }

            return $checkout->fresh('lines');
        }, 5);
    }

    /**
     * @param  array<int, array{line_id: int, returned_quantity: int, condition_in?: string|null, return_status?: string|null, notes_in?: string|null, photos_in: array<int, string>}>  $returns
     */
    public function returnItems(InventoryCheckout $checkout, array $returns, User $actor): InventoryCheckout
    {
        $returns = array_values(array_filter($returns, static fn (array $r): bool => (int) ($r['returned_quantity'] ?? 0) > 0));

        if ($returns === []) {
            throw new InventoryCheckoutException('Select at least one item to return.');
        }

        if ($checkout->status === CheckoutStatus::Returned) {
            throw InventoryCheckoutException::alreadyClosed((string) $checkout->reference);
        }

        $badReturns = DB::transaction(function () use ($checkout, $returns, $actor): array {
            $lineIds = array_map(static fn (array $r): int => (int) $r['line_id'], $returns);

            $lines = InventoryCheckoutItem::query()
                ->where('inventory_checkout_id', $checkout->getKey())
                ->whereIn('id', $lineIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $items = InventoryItem::query()
                ->withoutGlobalScope('branch')
                ->whereIn('id', $lines->pluck('inventory_item_id')->all())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $bad = [];

            foreach ($returns as $return) {
                $line = $lines->get((int) $return['line_id']);

                if (! $line instanceof InventoryCheckoutItem) {
                    throw new InventoryCheckoutException('One of the selected lines is not part of this checkout.');
                }

                $item = $items->get((int) $line->inventory_item_id);
                $itemName = (string) ($item?->name ?? 'Item');
                $photos = array_values(array_filter($return['photos_in'] ?? []));
                $quantity = (int) $return['returned_quantity'];

                $this->assertPhotoMinimum($itemName, $photos);

                $outstanding = $line->outstandingQuantity();

                if ($quantity > $outstanding) {
                    throw InventoryCheckoutException::returnTooLarge($itemName, $quantity, $outstanding);
                }

                $returnStatus = $return['return_status'] ?? ReturnStatus::ReturnedOk->value;

                $line->forceFill([
                    'returned_quantity' => $line->returned_quantity + $quantity,
                    'condition_in' => $return['condition_in'] ?? null,
                    'notes_in' => $return['notes_in'] ?? null,
                    'return_status' => $returnStatus,
                    'returned_at' => now(),
                    'received_by' => $actor->getKey(),
                ])->save();

                $this->storePhotos($line, $photos, PhotoStage::In, $actor);

                if ($item instanceof InventoryItem) {
                    $this->applyReturnToItem($item, $returnStatus, $return['condition_in'] ?? null);

                    if (in_array($returnStatus, [ReturnStatus::ReturnedDamaged->value, ReturnStatus::Missing->value], true)) {
                        $bad[] = [$item, $returnStatus];
                    }
                }
            }

            $checkout->load('lines');
            $checkout->refreshStatus();

            return $bad;
        }, 5);

        foreach ($badReturns as [$item, $returnStatus]) {
            $this->notifyBadReturn($checkout, $item, $returnStatus);
        }

        return $checkout->fresh('lines');
    }

    /**
     * Mark checkouts whose expected return has passed. Returns the ones that
     * newly changed, so the caller can notify.
     *
     * @return Collection<int, InventoryCheckout>
     */
    public function markOverdue(?Carbon $asOf = null): Collection
    {
        $asOf ??= now();

        $checkouts = InventoryCheckout::query()
            ->withoutGlobalScope('branch')
            ->whereIn('status', [CheckoutStatus::Out->value, CheckoutStatus::PartiallyReturned->value])
            ->where('expected_return_at', '<', $asOf)
            ->get();

        $checkouts->each(function (InventoryCheckout $checkout): void {
            $checkout->forceFill(['status' => CheckoutStatus::Overdue])->save();
        });

        return $checkouts;
    }

    /**
     * @param  array<int, string>  $photos
     */
    protected function assertPhotoMinimum(string $itemName, array $photos): void
    {
        if (count($photos) < self::MINIMUM_PHOTOS) {
            throw InventoryCheckoutException::notEnoughPhotos($itemName, count($photos), self::MINIMUM_PHOTOS);
        }
    }

    /**
     * @param  array<int, string>  $paths
     */
    protected function storePhotos(InventoryCheckoutItem $line, array $paths, PhotoStage $stage, User $actor): void
    {
        foreach ($paths as $path) {
            $line->photos()->create([
                'stage' => $stage->value,
                'path' => $path,
                'uploaded_by' => $actor->getKey(),
            ]);
        }
    }

    /**
     * Once nothing is left on the shelf, the item itself reads as checked out.
     */
    protected function markItemStatusAfterCheckout(InventoryItem $item): void
    {
        $item->refresh();

        if ($item->availableQuantity() > 0) {
            return;
        }

        if ($item->status === ItemStatus::CheckedOut) {
            return;
        }

        $item->forceFill([
            'status_before_checkout' => $item->status?->value,
            'status' => ItemStatus::CheckedOut,
        ])->save();
    }

    protected function applyReturnToItem(InventoryItem $item, string $returnStatus, ?string $conditionIn): void
    {
        $attributes = [];

        if ($returnStatus === ReturnStatus::ReturnedDamaged->value && $conditionIn !== null) {
            $attributes['condition'] = $conditionIn;
        }

        if ($returnStatus === ReturnStatus::Missing->value) {
            $attributes['status'] = ItemStatus::Missing;
            $attributes['status_before_checkout'] = null;
            $item->forceFill($attributes)->save();

            return;
        }

        $item->refresh();

        // Everything back? Put the item's status back where it was.
        if ($item->quantityOut() === 0 && $item->status === ItemStatus::CheckedOut) {
            $attributes['status'] = $item->status_before_checkout ?? ItemStatus::InUse;
            $attributes['status_before_checkout'] = null;
        }

        if ($attributes !== []) {
            $item->forceFill($attributes)->save();
        }
    }

    protected function notifyBadReturn(InventoryCheckout $checkout, InventoryItem $item, string $returnStatus): void
    {
        foreach ($this->branchWatchers((int) $checkout->branch_id) as $user) {
            $user->notify(new InventoryItemReturnedBadly($checkout, $item, $returnStatus));
        }
    }

    /**
     * Branch managers and inventory officers responsible for a branch.
     *
     * @return Collection<int, User>
     */
    public function branchWatchers(int $branchId): Collection
    {
        return User::query()
            ->where('branch_id', $branchId)
            ->where(function ($query): void {
                $query->whereIn('role', ['branch_manager', 'inventory_officer'])
                    ->orWhereHas('roles', function ($q): void {
                        $q->whereIn('name', ['branch_manager', 'inventory_officer']);
                    });
            })
            ->get();
    }
}
