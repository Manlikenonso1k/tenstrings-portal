# Checkout Line Model

> 20 nodes · cohesion 0.13

## Key Concepts

- **Illuminate\Database\Eloquent\Relations\HasMany** (23 connections)
- **InventoryCheckoutItem** (21 connections) — `app/Models/InventoryCheckoutItem.php`
- **InventoryCheckoutItem.php** (7 connections) — `app/Models/InventoryCheckoutItem.php`
- **.openCheckoutFor()** (5 connections) — `app/Filament/Inventory/Actions/CheckoutActions.php`
- **.photos()** (4 connections) — `app/Models/InventoryCheckoutItem.php`
- **.photosIn()** (3 connections) — `app/Models/InventoryCheckoutItem.php`
- **.photosOut()** (3 connections) — `app/Models/InventoryCheckoutItem.php`
- **.modules()** (2 connections) — `app/Models/Course.php`
- **.lessons()** (2 connections) — `app/Models/CourseModule.php`
- **.lines()** (2 connections) — `app/Models/InventoryCheckout.php`
- **.checkout()** (2 connections) — `app/Models/InventoryCheckoutItem.php`
- **.isFullyReturned()** (2 connections) — `app/Models/InventoryCheckoutItem.php`
- **.item()** (2 connections) — `app/Models/InventoryCheckoutItem.php`
- **.outstandingQuantity()** (2 connections) — `app/Models/InventoryCheckoutItem.php`
- **.receivedBy()** (2 connections) — `app/Models/InventoryCheckoutItem.php`
- **.auditLines()** (2 connections) — `app/Models/InventoryItem.php`
- **.movements()** (2 connections) — `app/Models/InventoryItem.php`
- **.transfers()** (2 connections) — `app/Models/InventoryItem.php`
- **.audits()** (2 connections) — `app/Models/InventoryRoom.php`
- **.items()** (2 connections) — `app/Models/InventoryRoom.php`

## Relationships

- [Inventory Item Model](Inventory_Item_Model.md) (6 shared connections)
- [Inventory Checkout Service](Inventory_Checkout_Service.md) (4 shared connections)
- [Movement & Photo Models](Movement_&_Photo_Models.md) (4 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (4 shared connections)
- [Core Domain Models](Core_Domain_Models.md) (3 shared connections)
- [Branch Scoping & Activity Log](Branch_Scoping_&_Activity_Log.md) (3 shared connections)
- [Checkout Model & Policy](Checkout_Model_&_Policy.md) (2 shared connections)
- [Course Module Model](Course_Module_Model.md) (2 shared connections)
- [Checkout Form Schema Inventory UI](Checkout_Form_Schema_Inventory_UI.md) (2 shared connections)
- [Inventory Room Model & Policy](Inventory_Room_Model_&_Policy.md) (2 shared connections)
- [Checkout Status Enum](Checkout_Status_Enum.md) (1 shared connections)
- [Checkout Action Wiring](Checkout_Action_Wiring.md) (1 shared connections)

## Source Files

- `app/Filament/Inventory/Actions/CheckoutActions.php`
- `app/Models/Course.php`
- `app/Models/CourseModule.php`
- `app/Models/InventoryCheckout.php`
- `app/Models/InventoryCheckoutItem.php`
- `app/Models/InventoryItem.php`
- `app/Models/InventoryRoom.php`

## Audit Trail

- EXTRACTED: 67 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*