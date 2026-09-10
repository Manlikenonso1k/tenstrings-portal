<?php

namespace Tests\Feature;

use App\Filament\Inventory\Resources\InventoryCheckoutResource;
use App\Filament\Inventory\Resources\InventoryItemResource;
use App\Filament\Inventory\Resources\InventoryItemResource\Pages\EditInventoryItem;
use App\Filament\Inventory\Resources\InventoryItemResource\RelationManagers\CheckoutHistoryRelationManager;
use App\Filament\Inventory\Resources\InventoryRoomResource;
use App\Filament\Inventory\Resources\InventoryRoomResource\Pages\ViewInventoryRoom;
use App\Filament\Inventory\Resources\InventoryRoomResource\RelationManagers\ItemsRelationManager;
use App\Services\Inventory\InventoryCheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Concerns\BuildsInventory;
use Tests\TestCase;

/**
 * Smoke tests for the pages themselves — they catch a resource that no longer
 * boots, which unit tests on the service never would.
 */
class InventoryPanelPagesTest extends TestCase
{
    use BuildsInventory;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedInventoryPermissions();
    }

    public function test_the_room_grid_lists_rooms_for_an_officer(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);

        $this->room($branch, 'Digital Studio');
        $this->room($branch, 'HR Office');

        $this->actingAs($officer)
            ->get(InventoryRoomResource::getUrl('index', panel: 'inventory'))
            ->assertOk()
            ->assertSee('Digital Studio')
            ->assertSee('HR Office')
            ->assertSee('All items');
    }

    public function test_a_room_page_shows_only_that_rooms_items(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);

        $studio = $this->room($branch, 'Digital Studio');
        $office = $this->room($branch, 'HR Office');

        $keyboard = $this->item($branch, $studio, name: 'Yamaha Keyboard');
        $printer = $this->item($branch, $office, name: 'Office Printer');

        $this->actingAs($officer)
            ->get(InventoryRoomResource::getUrl('view', ['record' => $studio], panel: 'inventory'))
            ->assertOk()
            ->assertSee('Digital Studio');

        // The items table is a relation manager, so it is its own Livewire
        // component and is not in the page's first response.
        Livewire::actingAs($officer)
            ->test(ItemsRelationManager::class, [
                'ownerRecord' => $studio,
                'pageClass' => ViewInventoryRoom::class,
            ])
            ->assertCanSeeTableRecords([$keyboard])
            ->assertCanNotSeeTableRecords([$printer]);
    }

    public function test_all_items_still_searches_across_every_room(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);

        $this->item($branch, $this->room($branch, 'Digital Studio'), name: 'Yamaha Keyboard');
        $this->item($branch, $this->room($branch, 'HR Office'), name: 'Office Printer');

        $this->actingAs($officer)
            ->get(InventoryItemResource::getUrl('index', panel: 'inventory'))
            ->assertOk()
            ->assertSee('Yamaha Keyboard')
            ->assertSee('Office Printer')
            ->assertSee('Rooms');
    }

    public function test_the_checkouts_page_loads_and_shows_a_reference(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);

        $checkout = app(InventoryCheckoutService::class)
            ->checkout($this->checkoutPayload($this->item($branch, quantity: 1)), $officer);

        $this->actingAs($officer)
            ->get(InventoryCheckoutResource::getUrl('index', panel: 'inventory'))
            ->assertOk()
            ->assertSee($checkout->reference);

        $this->actingAs($officer)
            ->get(InventoryCheckoutResource::getUrl('view', ['record' => $checkout], panel: 'inventory'))
            ->assertOk()
            ->assertSee('Christmas Carol Concert')
            ->assertSee('Ada Obi');
    }

    public function test_a_branch_manager_cannot_open_another_branchs_room(): void
    {
        $ajah = $this->branch('ajah-branch');
        $agege = $this->branch('agege-branch');

        $foreignRoom = $this->room($agege, 'Agege Studio');
        $manager = $this->userWithRole('branch_manager', $ajah);

        $this->actingAs($manager)
            ->get(InventoryRoomResource::getUrl('view', ['record' => $foreignRoom], panel: 'inventory'))
            ->assertNotFound();
    }

    public function test_an_items_edit_page_loads_with_its_checkout_history(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $item = $this->item($branch, $this->room($branch), quantity: 1);

        $checkout = app(InventoryCheckoutService::class)->checkout($this->checkoutPayload($item), $officer);
        $line = $checkout->lines->first();

        $this->actingAs($officer)
            ->get(InventoryItemResource::getUrl('edit', ['record' => $item], panel: 'inventory'))
            ->assertOk();

        Livewire::actingAs($officer)
            ->test(CheckoutHistoryRelationManager::class, [
                'ownerRecord' => $item,
                'pageClass' => EditInventoryItem::class,
            ])
            ->assertCanSeeTableRecords([$line])
            ->assertSee($checkout->reference);
    }

    public function test_the_ceo_reaches_the_checkouts_list(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        app(InventoryCheckoutService::class)
            ->checkout($this->checkoutPayload($this->item($branch, quantity: 1)), $officer);

        $ceo = $this->userWithRole('ceo');

        $this->actingAs($ceo)
            ->get(InventoryCheckoutResource::getUrl('index', panel: 'inventory'))
            ->assertOk();
    }
}
