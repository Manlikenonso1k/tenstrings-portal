<?php

namespace Tests\Feature;

use App\Enums\CheckoutStatus;
use App\Enums\ItemCondition;
use App\Enums\ItemStatus;
use App\Enums\ReturnStatus;
use App\Exceptions\InventoryCheckoutException;
use App\Notifications\InventoryCheckoutOverdue;
use App\Notifications\InventoryItemReturnedBadly;
use App\Services\Inventory\InventoryCheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\Concerns\BuildsInventory;
use Tests\TestCase;

class InventoryCheckoutServiceTest extends TestCase
{
    use BuildsInventory;
    use RefreshDatabase;

    protected InventoryCheckoutService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedInventoryPermissions();
        $this->service = app(InventoryCheckoutService::class);
    }

    public function test_checkout_is_refused_when_a_line_has_fewer_than_two_photos(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $item = $this->item($branch, $this->room($branch));

        $this->expectException(InventoryCheckoutException::class);
        $this->expectExceptionMessage('2 photos are required, 1 given');

        $this->service->checkout($this->checkoutPayload($item, photos: 1), $officer);
    }

    public function test_a_refused_checkout_leaves_nothing_behind(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $item = $this->item($branch);

        // No photos at all, called straight at the service — the wizard is not
        // in the way here, so only the service-side rule can stop it.
        try {
            $this->service->checkout($this->checkoutPayload($item, photos: 0), $officer);
            $this->fail('Expected the checkout to be refused.');
        } catch (InventoryCheckoutException $exception) {
            $this->assertStringContainsString('2 photos are required', $exception->getMessage());
        }

        // The header row is written before the lines are validated, so this
        // also proves the transaction rolls the whole thing back.
        $this->assertDatabaseCount('inventory_checkouts', 0);
        $this->assertDatabaseCount('inventory_checkout_items', 0);
        $this->assertDatabaseCount('inventory_checkout_photos', 0);
    }

    public function test_checkout_is_refused_beyond_the_available_quantity(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $item = $this->item($branch, quantity: 2);

        $this->expectException(InventoryCheckoutException::class);
        $this->expectExceptionMessage('only 2 available, 3 requested');

        $this->service->checkout($this->checkoutPayload($item, quantity: 3), $officer);
    }

    public function test_checkout_is_refused_for_an_item_under_repair(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $item = $this->item($branch, status: ItemStatus::UnderRepair);

        $this->expectException(InventoryCheckoutException::class);
        $this->expectExceptionMessage('cannot leave');

        $this->service->checkout($this->checkoutPayload($item), $officer);
    }

    public function test_a_second_checkout_cannot_take_the_last_unit(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $item = $this->item($branch, quantity: 1);

        $this->service->checkout($this->checkoutPayload($item, quantity: 1), $officer);

        // Availability is recomputed inside the locked transaction, so the
        // second attempt sees nothing left even though the item row still says
        // quantity 1.
        $this->expectException(InventoryCheckoutException::class);
        $this->expectExceptionMessage('only 0 available');

        $this->service->checkout($this->checkoutPayload($item, quantity: 1), $officer);
    }

    public function test_checking_out_everything_marks_the_item_checked_out_and_remembers_its_status(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $item = $this->item($branch, quantity: 2, status: ItemStatus::InStorage);

        $checkout = $this->service->checkout($this->checkoutPayload($item, quantity: 2), $officer);

        $item->refresh();

        $this->assertSame(ItemStatus::CheckedOut, $item->status);
        $this->assertSame(ItemStatus::InStorage, $item->status_before_checkout);
        $this->assertSame(0, $item->availableQuantity());
        $this->assertSame(CheckoutStatus::Out, $checkout->status);
        $this->assertStringStartsWith('CO-' . now()->year . '-', (string) $checkout->reference);
    }

    public function test_a_partial_checkout_leaves_the_item_status_alone(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $item = $this->item($branch, quantity: 3);

        $this->service->checkout($this->checkoutPayload($item, quantity: 1), $officer);

        $item->refresh();

        $this->assertSame(ItemStatus::InUse, $item->status);
        $this->assertSame(2, $item->availableQuantity());
        $this->assertSame(1, $item->quantityOut());
    }

    public function test_partial_return_then_full_return_moves_the_statuses_correctly(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $item = $this->item($branch, quantity: 2, status: ItemStatus::InUse);

        $checkout = $this->service->checkout($this->checkoutPayload($item, quantity: 2), $officer);
        $line = $checkout->lines->first();

        $checkout = $this->service->returnItems($checkout, [[
            'line_id' => $line->getKey(),
            'returned_quantity' => 1,
            'condition_in' => ItemCondition::Good->value,
            'return_status' => ReturnStatus::ReturnedOk->value,
            'photos_in' => $this->fakePhotos(2),
        ]], $officer);

        $this->assertSame(CheckoutStatus::PartiallyReturned, $checkout->status);
        $this->assertSame(ItemStatus::CheckedOut, $item->fresh()->status);

        $checkout = $this->service->returnItems($checkout, [[
            'line_id' => $line->getKey(),
            'returned_quantity' => 1,
            'condition_in' => ItemCondition::Good->value,
            'return_status' => ReturnStatus::ReturnedOk->value,
            'photos_in' => $this->fakePhotos(2),
        ]], $officer);

        $item->refresh();

        $this->assertSame(CheckoutStatus::Returned, $checkout->status);
        $this->assertSame(ItemStatus::InUse, $item->status);
        $this->assertNull($item->status_before_checkout);
        $this->assertSame(2, $item->availableQuantity());
    }

    public function test_a_return_is_refused_without_two_photos(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $item = $this->item($branch, quantity: 1);

        $checkout = $this->service->checkout($this->checkoutPayload($item), $officer);
        $line = $checkout->lines->first();

        $this->expectException(InventoryCheckoutException::class);
        $this->expectExceptionMessage('2 photos are required, 1 given');

        $this->service->returnItems($checkout, [[
            'line_id' => $line->getKey(),
            'returned_quantity' => 1,
            'photos_in' => $this->fakePhotos(1),
        ]], $officer);
    }

    public function test_returning_more_than_is_out_is_refused(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $item = $this->item($branch, quantity: 3);

        $checkout = $this->service->checkout($this->checkoutPayload($item, quantity: 1), $officer);
        $line = $checkout->lines->first();

        $this->expectException(InventoryCheckoutException::class);
        $this->expectExceptionMessage('only 1 still out');

        $this->service->returnItems($checkout, [[
            'line_id' => $line->getKey(),
            'returned_quantity' => 2,
            'photos_in' => $this->fakePhotos(2),
        ]], $officer);
    }

    public function test_a_damaged_return_updates_the_item_condition_and_notifies_the_branch(): void
    {
        Notification::fake();

        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $manager = $this->userWithRole('branch_manager', $branch);
        $item = $this->item($branch, quantity: 1);

        $checkout = $this->service->checkout($this->checkoutPayload($item), $officer);
        $line = $checkout->lines->first();

        $this->service->returnItems($checkout, [[
            'line_id' => $line->getKey(),
            'returned_quantity' => 1,
            'condition_in' => ItemCondition::Damaged->value,
            'return_status' => ReturnStatus::ReturnedDamaged->value,
            'photos_in' => $this->fakePhotos(2),
        ]], $officer);

        $this->assertSame(ItemCondition::Damaged, $item->fresh()->condition);

        Notification::assertSentTo($manager, InventoryItemReturnedBadly::class);
        Notification::assertSentTo($officer, InventoryItemReturnedBadly::class);
    }

    public function test_a_missing_return_marks_the_item_missing(): void
    {
        Notification::fake();

        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $item = $this->item($branch, quantity: 1);

        $checkout = $this->service->checkout($this->checkoutPayload($item), $officer);

        $this->service->returnItems($checkout, [[
            'line_id' => $checkout->lines->first()->getKey(),
            'returned_quantity' => 1,
            'condition_in' => ItemCondition::Poor->value,
            'return_status' => ReturnStatus::Missing->value,
            'photos_in' => $this->fakePhotos(2),
        ]], $officer);

        $this->assertSame(ItemStatus::Missing, $item->fresh()->status);
    }

    public function test_photos_are_recorded_against_the_right_stage(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $item = $this->item($branch, quantity: 1);

        $checkout = $this->service->checkout($this->checkoutPayload($item, photos: 3), $officer);
        $line = $checkout->lines->first();

        $this->assertCount(3, $line->photosOut()->get());
        $this->assertCount(0, $line->photosIn()->get());

        $this->service->returnItems($checkout, [[
            'line_id' => $line->getKey(),
            'returned_quantity' => 1,
            'condition_in' => ItemCondition::Good->value,
            'return_status' => ReturnStatus::ReturnedOk->value,
            'photos_in' => $this->fakePhotos(2),
        ]], $officer);

        $this->assertCount(3, $line->photosOut()->get());
        $this->assertCount(2, $line->photosIn()->get());
    }

    public function test_the_overdue_command_marks_and_notifies(): void
    {
        Notification::fake();

        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $manager = $this->userWithRole('branch_manager', $branch);
        $item = $this->item($branch, quantity: 1);

        $checkout = $this->service->checkout(
            $this->checkoutPayload($item, overrides: ['expected_return_at' => now()->addHours(2)]),
            $officer,
        );

        // Move the deadline into the past without touching the status.
        $checkout->forceFill(['expected_return_at' => now()->subDay()])->save();

        $this->artisan('inventory:mark-overdue')->assertSuccessful();

        $this->assertSame(CheckoutStatus::Overdue, $checkout->fresh()->status);

        Notification::assertSentTo($manager, InventoryCheckoutOverdue::class);
        Notification::assertSentTo($officer, InventoryCheckoutOverdue::class);
    }

    public function test_a_returned_checkout_is_not_marked_overdue(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $item = $this->item($branch, quantity: 1);

        $checkout = $this->service->checkout($this->checkoutPayload($item), $officer);

        $this->service->returnItems($checkout, [[
            'line_id' => $checkout->lines->first()->getKey(),
            'returned_quantity' => 1,
            'condition_in' => ItemCondition::Good->value,
            'return_status' => ReturnStatus::ReturnedOk->value,
            'photos_in' => $this->fakePhotos(2),
        ]], $officer);

        $checkout->forceFill(['expected_return_at' => now()->subDay()])->save();

        $this->artisan('inventory:mark-overdue')->assertSuccessful();

        $this->assertSame(CheckoutStatus::Returned, $checkout->fresh()->status);
    }
}
