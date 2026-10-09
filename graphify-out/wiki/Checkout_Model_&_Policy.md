# Checkout Model & Policy

> 16 nodes · cohesion 0.18

## Key Concepts

- **InventoryCheckout** (46 connections) — `app/Models/InventoryCheckout.php`
- **InventoryCheckoutPolicy** (11 connections) — `app/Policies/InventoryCheckoutPolicy.php`
- **InventoryCheckoutPolicy.php** (4 connections) — `app/Policies/InventoryCheckoutPolicy.php`
- **.refreshStatus()** (3 connections) — `app/Models/InventoryCheckout.php`
- **.delete()** (3 connections) — `app/Policies/InventoryCheckoutPolicy.php`
- **.managePhotos()** (3 connections) — `app/Policies/InventoryCheckoutPolicy.php`
- **.return()** (3 connections) — `app/Policies/InventoryCheckoutPolicy.php`
- **.update()** (3 connections) — `app/Policies/InventoryCheckoutPolicy.php`
- **.view()** (3 connections) — `app/Policies/InventoryCheckoutPolicy.php`
- **.hasAnyReturn()** (2 connections) — `app/Models/InventoryCheckout.php`
- **.isFullyReturned()** (2 connections) — `app/Models/InventoryCheckout.php`
- **.nextReference()** (2 connections) — `app/Models/InventoryCheckout.php`
- **.scopeOverdue()** (2 connections) — `app/Models/InventoryCheckout.php`
- **.create()** (2 connections) — `app/Policies/InventoryCheckoutPolicy.php`
- **.deleteAny()** (2 connections) — `app/Policies/InventoryCheckoutPolicy.php`
- **.viewAny()** (2 connections) — `app/Policies/InventoryCheckoutPolicy.php`

## Relationships

- [User Model & Panel Access](User_Model_&_Panel_Access.md) (9 shared connections)
- [Checkout Notifications & Overdue](Checkout_Notifications_&_Overdue.md) (6 shared connections)
- [Branch Scoping & Activity Log](Branch_Scoping_&_Activity_Log.md) (6 shared connections)
- [Inventory Checkout Service](Inventory_Checkout_Service.md) (5 shared connections)
- [Checkout Action Wiring](Checkout_Action_Wiring.md) (5 shared connections)
- [Feature Test Harness](Feature_Test_Harness.md) (2 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (2 shared connections)
- [Checkout Form Schema Inventory UI](Checkout_Form_Schema_Inventory_UI.md) (2 shared connections)
- [Inventory Room Model & Policy](Inventory_Room_Model_&_Policy.md) (2 shared connections)
- [Checkout Line Model](Checkout_Line_Model.md) (2 shared connections)
- [Movement & Photo Models](Movement_&_Photo_Models.md) (2 shared connections)
- [Checkout Status Enum](Checkout_Status_Enum.md) (2 shared connections)

## Source Files

- `app/Models/InventoryCheckout.php`
- `app/Policies/InventoryCheckoutPolicy.php`

## Audit Trail

- EXTRACTED: 70 (99%)
- INFERRED: 1 (1%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*