# Inventory Item Model

> 22 nodes · cohesion 0.14

## Key Concepts

- **InventoryItem** (66 connections) — `app/Models/InventoryItem.php`
- **AssetTagGenerator** (7 connections) — `app/Support/AssetTagGenerator.php`
- **InventoryItemObserver.php** (6 connections) — `app/Observers/InventoryItemObserver.php`
- **InventoryItemObserver** (6 connections) — `app/Observers/InventoryItemObserver.php`
- **.openCheckoutLines()** (5 connections) — `app/Models/InventoryItem.php`
- **.syncBranchFromRoom()** (5 connections) — `app/Observers/InventoryItemObserver.php`
- **AssetTagGenerator.php** (5 connections) — `app/Support/AssetTagGenerator.php`
- **.creating()** (4 connections) — `app/Observers/InventoryItemObserver.php`
- **.checkoutLines()** (3 connections) — `app/Models/InventoryItem.php`
- **.quantityOut()** (3 connections) — `app/Models/InventoryItem.php`
- **.updated()** (3 connections) — `app/Observers/InventoryItemObserver.php`
- **.updating()** (3 connections) — `app/Observers/InventoryItemObserver.php`
- **.canCheckout()** (2 connections) — `app/Filament/Inventory/Actions/CheckoutActions.php`
- **.availableQuantity()** (2 connections) — `app/Models/InventoryItem.php`
- **.branch()** (2 connections) — `app/Models/InventoryItem.php`
- **.category()** (2 connections) — `app/Models/InventoryItem.php`
- **.creator()** (2 connections) — `app/Models/InventoryItem.php`
- **.room()** (2 connections) — `app/Models/InventoryItem.php`
- **.updater()** (2 connections) — `app/Models/InventoryItem.php`
- **.branchCode()** (2 connections) — `app/Support/AssetTagGenerator.php`
- **.categoryAbbreviation()** (2 connections) — `app/Support/AssetTagGenerator.php`
- **.nextSequence()** (1 connections) — `app/Support/AssetTagGenerator.php`

## Relationships

- [Item Policy & Branch Access](Item_Policy_&_Branch_Access.md) (8 shared connections)
- [Movement & Photo Models](Movement_&_Photo_Models.md) (7 shared connections)
- [Inventory Checkout Service](Inventory_Checkout_Service.md) (6 shared connections)
- [Checkout Line Model](Checkout_Line_Model.md) (6 shared connections)
- [Branch Scoping & Activity Log](Branch_Scoping_&_Activity_Log.md) (6 shared connections)
- [Inventory Room Model & Policy](Inventory_Room_Model_&_Policy.md) (5 shared connections)
- [Branch Model & Test Builders](Branch_Model_&_Test_Builders.md) (4 shared connections)
- [Checkout Action Wiring](Checkout_Action_Wiring.md) (3 shared connections)
- [Ajah Inventory Importer](Ajah_Inventory_Importer.md) (3 shared connections)
- [Checkout Notifications & Overdue](Checkout_Notifications_&_Overdue.md) (3 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (3 shared connections)
- [Inventory Category Model](Inventory_Category_Model.md) (3 shared connections)

## Source Files

- `app/Filament/Inventory/Actions/CheckoutActions.php`
- `app/Models/InventoryItem.php`
- `app/Observers/InventoryItemObserver.php`
- `app/Support/AssetTagGenerator.php`

## Audit Trail

- EXTRACTED: 103 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*