# Branch Model & Test Builders

> 16 nodes · cohesion 0.17

## Key Concepts

- **Branch** (23 connections) — `app/Models/Branch.php`
- **BuildsInventory** (17 connections) — `tests/Concerns/BuildsInventory.php`
- **.item()** (7 connections) — `tests/Concerns/BuildsInventory.php`
- **.findByStudentBranch()** (3 connections) — `app/Models/Branch.php`
- **.getComputedCourseFeeAttribute()** (3 connections) — `app/Models/Student.php`
- **.category()** (3 connections) — `tests/Concerns/BuildsInventory.php`
- **.checkoutPayload()** (3 connections) — `tests/Concerns/BuildsInventory.php`
- **.room()** (3 connections) — `tests/Concerns/BuildsInventory.php`
- **.userWithRole()** (3 connections) — `tests/Concerns/BuildsInventory.php`
- **.getOptionsFormComponents()** (2 connections) — `app/Filament/Inventory/Imports/AjahInventoryImporter.php`
- **Branch.php** (2 connections) — `app/Models/Branch.php`
- **.effectiveFeeFor()** (2 connections) — `app/Models/Branch.php`
- **.branch()** (2 connections) — `tests/Concerns/BuildsInventory.php`
- **.fakePhotos()** (2 connections) — `tests/Concerns/BuildsInventory.php`
- **self** (1 connections)
- **.seedInventoryPermissions()** (1 connections) — `tests/Concerns/BuildsInventory.php`

## Relationships

- [Inventory Item Model](Inventory_Item_Model.md) (4 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (4 shared connections)
- [Ajah Inventory Importer](Ajah_Inventory_Importer.md) (3 shared connections)
- [Inventory Category Model](Inventory_Category_Model.md) (3 shared connections)
- [Core Domain Models](Core_Domain_Models.md) (2 shared connections)
- [Checkout Resource & Page Tests](Checkout_Resource_&_Page_Tests.md) (2 shared connections)
- [Portal & LMS Controllers](Portal_&_LMS_Controllers.md) (2 shared connections)
- [Feature Test Harness](Feature_Test_Harness.md) (2 shared connections)
- [Inventory Room Photo Test Feature Test](Inventory_Room_Photo_Test_Feature_Test.md) (2 shared connections)
- [User Model & Panel Access](User_Model_&_Panel_Access.md) (2 shared connections)
- [Inventory Room Model & Policy](Inventory_Room_Model_&_Policy.md) (2 shared connections)
- [Student Importer](Student_Importer.md) (1 shared connections)

## Source Files

- `app/Filament/Inventory/Imports/AjahInventoryImporter.php`
- `app/Models/Branch.php`
- `app/Models/Student.php`
- `tests/Concerns/BuildsInventory.php`

## Audit Trail

- EXTRACTED: 54 (95%)
- INFERRED: 3 (5%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*