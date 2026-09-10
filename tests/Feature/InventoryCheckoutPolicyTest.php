<?php

namespace Tests\Feature;

use App\Models\InventoryCheckout;
use App\Models\InventoryItem;
use App\Models\InventoryRoom;
use App\Services\Inventory\InventoryCheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\BuildsInventory;
use Tests\TestCase;

class InventoryCheckoutPolicyTest extends TestCase
{
    use BuildsInventory;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedInventoryPermissions();
    }

    public function test_ceo_can_view_checkouts_but_cannot_create_or_return(): void
    {
        $ajah = $this->branch('ajah-branch');
        $officer = $this->userWithRole('inventory_officer', $ajah);
        $ceo = $this->userWithRole('ceo');

        $checkout = app(InventoryCheckoutService::class)
            ->checkout($this->checkoutPayload($this->item($ajah, quantity: 1)), $officer);

        $this->assertTrue($ceo->can('inventory_checkout.view'));
        $this->assertTrue($ceo->can('view', $checkout));

        $this->assertFalse($ceo->can('inventory_checkout.create'));
        $this->assertFalse($ceo->can('inventory_checkout.return'));
        $this->assertFalse($ceo->can('return', $checkout));
    }

    public function test_ceo_sees_checkouts_from_every_branch(): void
    {
        $ajah = $this->branch('ajah-branch');
        $agege = $this->branch('agege-branch');

        $ajahOfficer = $this->userWithRole('inventory_officer', $ajah);
        $agegeOfficer = $this->userWithRole('inventory_officer', $agege);
        $service = app(InventoryCheckoutService::class);

        $service->checkout($this->checkoutPayload($this->item($ajah, quantity: 1)), $ajahOfficer);
        $service->checkout($this->checkoutPayload($this->item($agege, quantity: 1)), $agegeOfficer);

        $ceo = $this->userWithRole('ceo');

        $this->actingAs($ceo);
        $this->assertSame(2, InventoryCheckout::query()->count());

        $this->actingAs($ajahOfficer);
        $this->assertSame(1, InventoryCheckout::query()->count());
    }

    public function test_an_officer_holds_all_three_checkout_permissions(): void
    {
        $officer = $this->userWithRole('inventory_officer', $this->branch());

        $this->assertTrue($officer->can('inventory_checkout.view'));
        $this->assertTrue($officer->can('inventory_checkout.create'));
        $this->assertTrue($officer->can('inventory_checkout.return'));
    }

    public function test_a_branch_manager_cannot_view_or_return_another_branchs_checkout(): void
    {
        $ajah = $this->branch('ajah-branch');
        $agege = $this->branch('agege-branch');

        $ajahOfficer = $this->userWithRole('inventory_officer', $ajah);
        $checkout = app(InventoryCheckoutService::class)
            ->checkout($this->checkoutPayload($this->item($ajah, quantity: 1)), $ajahOfficer);

        $foreignManager = $this->userWithRole('branch_manager', $agege);

        $this->assertFalse($foreignManager->can('view', $checkout));
        $this->assertFalse($foreignManager->can('return', $checkout));

        $ownManager = $this->userWithRole('branch_manager', $ajah);

        $this->assertTrue($ownManager->can('view', $checkout));
        $this->assertTrue($ownManager->can('return', $checkout));
    }

    public function test_branch_scoping_hides_other_branches_records(): void
    {
        $ajah = $this->branch('ajah-branch');
        $agege = $this->branch('agege-branch');

        $this->item($ajah, $this->room($ajah, 'Ajah Studio'), quantity: 2);
        $this->item($agege, $this->room($agege, 'Agege Studio'), quantity: 2);

        $manager = $this->userWithRole('branch_manager', $ajah);

        $this->actingAs($manager);

        $this->assertSame(1, InventoryItem::query()->count());
        $this->assertSame(1, InventoryRoom::query()->count());
        $this->assertSame($ajah->getKey(), InventoryItem::query()->first()->branch_id);
    }

    public function test_an_officer_never_sees_purchase_costs(): void
    {
        $officer = $this->userWithRole('inventory_officer', $this->branch());
        $ceo = $this->userWithRole('ceo');

        $this->assertFalse($officer->can('inventory.view_costs'));
        $this->assertTrue($ceo->can('inventory.view_costs'));
    }

    public function test_an_officer_cannot_delete_or_dispose_items(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);
        $item = $this->item($branch);

        $this->assertFalse($officer->can('item.delete'));
        $this->assertFalse($officer->can('item.dispose'));
        $this->assertFalse($officer->can('delete', $item));
        $this->assertFalse($officer->can('dispose', $item));
    }

    public function test_a_super_admin_bypasses_every_inventory_gate(): void
    {
        $superAdmin = $this->userWithRole('super_admin');

        $this->assertTrue($superAdmin->can('inventory_checkout.create'));
        $this->assertTrue($superAdmin->can('inventory.view_costs'));
        $this->assertTrue($superAdmin->can('item.dispose'));
    }
}
