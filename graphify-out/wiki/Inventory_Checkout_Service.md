# Inventory Checkout Service

> 15 nodes · cohesion 0.26

## Key Concepts

- **InventoryCheckoutService** (19 connections) — `app/Services/Inventory/InventoryCheckoutService.php`
- **InventoryCheckoutService.php** (15 connections) — `app/Services/Inventory/InventoryCheckoutService.php`
- **.returnItems()** (11 connections) — `app/Services/Inventory/InventoryCheckoutService.php`
- **.checkout()** (9 connections) — `app/Services/Inventory/InventoryCheckoutService.php`
- **PhotoStage** (8 connections) — `app/Enums/PhotoStage.php`
- **.notifyBadReturn()** (6 connections) — `app/Services/Inventory/InventoryCheckoutService.php`
- **.storePhotos()** (6 connections) — `app/Services/Inventory/InventoryCheckoutService.php`
- **.assertPhotoMinimum()** (4 connections) — `app/Services/Inventory/InventoryCheckoutService.php`
- **.branchWatchers()** (4 connections) — `app/Services/Inventory/InventoryCheckoutService.php`
- **.markOverdue()** (4 connections) — `app/Services/Inventory/InventoryCheckoutService.php`
- **.applyReturnToItem()** (3 connections) — `app/Services/Inventory/InventoryCheckoutService.php`
- **.markItemStatusAfterCheckout()** (3 connections) — `app/Services/Inventory/InventoryCheckoutService.php`
- **Illuminate\Support\Collection** (3 connections)
- **PhotoStage.php** (1 connections) — `app/Enums/PhotoStage.php`
- **.label()** (1 connections) — `app/Enums/PhotoStage.php`

## Relationships

- [Inventory Item Model](Inventory_Item_Model.md) (6 shared connections)
- [Checkout Model & Policy](Checkout_Model_&_Policy.md) (5 shared connections)
- [User Model & Panel Access](User_Model_&_Panel_Access.md) (5 shared connections)
- [Checkout Notifications & Overdue](Checkout_Notifications_&_Overdue.md) (5 shared connections)
- [Checkout Line Model](Checkout_Line_Model.md) (4 shared connections)
- [Inventory Checkout Exception](Inventory_Checkout_Exception.md) (4 shared connections)
- [Student CSV Import Command](Student_CSV_Import_Command.md) (2 shared connections)
- [Item Condition & Status Enums](Item_Condition_&_Status_Enums.md) (2 shared connections)
- [Checkout Service Tests](Checkout_Service_Tests.md) (2 shared connections)
- [Movement & Photo Models](Movement_&_Photo_Models.md) (1 shared connections)
- [Branch Enrollment Doughnut Dashboard Widget (2)](Branch_Enrollment_Doughnut_Dashboard_Widget_2.md) (1 shared connections)
- [Checkout Status Enum](Checkout_Status_Enum.md) (1 shared connections)

## Source Files

- `app/Enums/PhotoStage.php`
- `app/Services/Inventory/InventoryCheckoutService.php`

## Audit Trail

- EXTRACTED: 70 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*