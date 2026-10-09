# Inventory Category Model

> 11 nodes · cohesion 0.24

## Key Concepts

- **InventoryCategory** (19 connections) — `app/Models/InventoryCategory.php`
- **BuildsInventory.php** (10 connections) — `tests/Concerns/BuildsInventory.php`
- **InventoryPermissionSeeder.php** (6 connections) — `database/seeders/InventoryPermissionSeeder.php`
- **InventoryPermissionSeeder** (4 connections) — `database/seeders/InventoryPermissionSeeder.php`
- **.resolveCategory()** (2 connections) — `app/Filament/Inventory/Imports/AjahInventoryImporter.php`
- **.booted()** (2 connections) — `app/Models/InventoryCategory.php`
- **.items()** (2 connections) — `app/Models/InventoryCategory.php`
- **.run()** (2 connections) — `database/seeders/InventoryPermissionSeeder.php`
- **Spatie\Permission\PermissionRegistrar** (2 connections)
- **.tagAbbreviation()** (1 connections) — `app/Models/InventoryCategory.php`
- **Spatie\Permission\Models\Permission** (1 connections)

## Relationships

- [User Model & Panel Access](User_Model_&_Panel_Access.md) (5 shared connections)
- [Ajah Inventory Importer](Ajah_Inventory_Importer.md) (3 shared connections)
- [Inventory Item Model](Inventory_Item_Model.md) (3 shared connections)
- [Branch Model & Test Builders](Branch_Model_&_Test_Builders.md) (3 shared connections)
- [Inventory Room Model & Policy](Inventory_Room_Model_&_Policy.md) (2 shared connections)
- [Instructor & Course Seeders](Instructor_&_Course_Seeders.md) (2 shared connections)
- [Item Condition & Status Enums](Item_Condition_&_Status_Enums.md) (2 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (1 shared connections)
- [Core Domain Models](Core_Domain_Models.md) (1 shared connections)
- [Checkout Action Wiring](Checkout_Action_Wiring.md) (1 shared connections)
- [Checkout Line Model](Checkout_Line_Model.md) (1 shared connections)
- [User Resource Admin](User_Resource_Admin.md) (1 shared connections)

## Source Files

- `app/Filament/Inventory/Imports/AjahInventoryImporter.php`
- `app/Models/InventoryCategory.php`
- `database/seeders/InventoryPermissionSeeder.php`
- `tests/Concerns/BuildsInventory.php`

## Audit Trail

- EXTRACTED: 37 (97%)
- INFERRED: 1 (3%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*