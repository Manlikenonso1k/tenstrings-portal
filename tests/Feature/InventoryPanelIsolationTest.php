<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class InventoryPanelIsolationTest extends TestCase
{
    use RefreshDatabase;
    public function test_inventory_officer_is_forbidden_from_admin_and_non_inventory_resources(): void
    {
        $officer = User::factory()->create(['role' => 'inventory_officer']);
        $this->actingAs($officer)->get('/admin')->assertForbidden();
        $this->actingAs($officer)->get('/admin/students')->assertForbidden();
        $this->actingAs($officer)->get('/admin/payments')->assertForbidden();
    }
}