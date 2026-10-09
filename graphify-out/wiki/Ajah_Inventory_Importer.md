# Ajah Inventory Importer

> 18 nodes · cohesion 0.14

## Key Concepts

- **StudentImporter.php** (17 connections) — `app/Filament/Imports/StudentImporter.php`
- **AjahInventoryImporter** (13 connections) — `app/Filament/Inventory/Imports/AjahInventoryImporter.php`
- **AjahInventoryImporter.php** (11 connections) — `app/Filament/Inventory/Imports/AjahInventoryImporter.php`
- **Illuminate\Support\Str** (11 connections)
- **InventoryCategory.php** (4 connections) — `app/Models/InventoryCategory.php`
- **Filament\Actions\Imports\Importer** (4 connections)
- **.beforeSave()** (2 connections) — `app/Filament/Inventory/Imports/AjahInventoryImporter.php`
- **.resolveRecord()** (2 connections) — `app/Filament/Inventory/Imports/AjahInventoryImporter.php`
- **.resolveRoom()** (2 connections) — `app/Filament/Inventory/Imports/AjahInventoryImporter.php`
- **Filament\Actions\Imports\Exceptions\RowImportFailedException** (2 connections)
- **Filament\Actions\Imports\ImportColumn** (2 connections)
- **.getColumns()** (1 connections) — `app/Filament/Inventory/Imports/AjahInventoryImporter.php`
- **.getJobConnection()** (1 connections) — `app/Filament/Inventory/Imports/AjahInventoryImporter.php`
- **.normaliseAssetTag()** (1 connections) — `app/Filament/Inventory/Imports/AjahInventoryImporter.php`
- **.splitQuantityAndName()** (1 connections) — `app/Filament/Inventory/Imports/AjahInventoryImporter.php`
- **cache.php** (1 connections) — `config/cache.php`
- **database.php** (1 connections) — `config/database.php`
- **session.php** (1 connections) — `config/session.php`

## Relationships

- [TGIPay & Webhook Controllers](TGIPay_&_Webhook_Controllers.md) (4 shared connections)
- [Active Import Tracker Dashboard Widget](Active_Import_Tracker_Dashboard_Widget.md) (3 shared connections)
- [Branch Model & Test Builders](Branch_Model_&_Test_Builders.md) (3 shared connections)
- [Inventory Category Model](Inventory_Category_Model.md) (3 shared connections)
- [Inventory Item Model](Inventory_Item_Model.md) (3 shared connections)
- [Inventory Room Model & Policy](Inventory_Room_Model_&_Policy.md) (3 shared connections)
- [Payments Page & Portal Settings](Payments_Page_&_Portal_Settings.md) (2 shared connections)
- [Student Importer](Student_Importer.md) (2 shared connections)
- [User Model & Panel Access](User_Model_&_Panel_Access.md) (1 shared connections)
- [Course Catalog Support](Course_Catalog_Support.md) (1 shared connections)
- [Student CSV Import Command](Student_CSV_Import_Command.md) (1 shared connections)
- [Portal & LMS Controllers](Portal_&_LMS_Controllers.md) (1 shared connections)

## Source Files

- `app/Filament/Imports/StudentImporter.php`
- `app/Filament/Inventory/Imports/AjahInventoryImporter.php`
- `app/Models/InventoryCategory.php`
- `config/cache.php`
- `config/database.php`
- `config/session.php`

## Audit Trail

- EXTRACTED: 56 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*