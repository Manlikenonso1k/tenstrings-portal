<?php
namespace Database\Seeders;
use App\Models\InventoryCategory;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
class InventoryPermissionSeeder extends Seeder {
 public function run(): void {
  app(PermissionRegistrar::class)->forgetCachedPermissions();
  foreach (config('inventory.permissions') as $name) { Permission::findOrCreate($name, 'web'); }
  $roles = [
   'ceo' => ['inventory.view','inventory.export','room.view','item.view','audit.view','inventory.view_all_branches','inventory.view_costs'],
   'inventory_officer' => config('inventory.roles.inventory_officer'),
   'branch_manager' => config('inventory.roles.branch_manager'),
   'super_admin' => config('inventory.permissions'),
  ];
  foreach ($roles as $name => $permissions) { $role = Role::findOrCreate($name, 'web'); $role->syncPermissions($permissions); }
  foreach (['Furniture','IT Equipment','Musical Instruments','Appliances','Audio/Visual','Electrical Fittings','Office Supplies','Teaching Aids'] as $name) { InventoryCategory::firstOrCreate(['slug' => str($name)->slug()], ['name'=>$name,'is_active'=>true]); }
 }
}