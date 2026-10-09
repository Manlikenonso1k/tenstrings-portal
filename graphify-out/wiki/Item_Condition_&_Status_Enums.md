# Item Condition & Status Enums

> 20 nodes · cohesion 0.12

## Key Concepts

- **ItemCondition** (20 connections) — `app/Enums/ItemCondition.php`
- **ItemStatus** (18 connections) — `app/Enums/ItemStatus.php`
- **.scopeNeedsAttention()** (4 connections) — `app/Models/InventoryItem.php`
- **.checkoutPhotoSections()** (3 connections) — `app/Filament/Inventory/Actions/CheckoutFormSchema.php`
- **.itemFilters()** (3 connections) — `app/Filament/Inventory/Resources/InventoryItemResource.php`
- **.needsAttention()** (3 connections) — `app/Models/InventoryItem.php`
- **.label()** (2 connections) — `app/Enums/ItemCondition.php`
- **.options()** (2 connections) — `app/Enums/ItemCondition.php`
- **.label()** (2 connections) — `app/Enums/ItemStatus.php`
- **.options()** (2 connections) — `app/Enums/ItemStatus.php`
- **.isBlockedFromCheckout()** (2 connections) — `app/Models/InventoryItem.php`
- **ItemCondition.php** (1 connections) — `app/Enums/ItemCondition.php`
- **.color()** (1 connections) — `app/Enums/ItemCondition.php`
- **.needingAttention()** (1 connections) — `app/Enums/ItemCondition.php`
- **.values()** (1 connections) — `app/Enums/ItemCondition.php`
- **ItemStatus.php** (1 connections) — `app/Enums/ItemStatus.php`
- **.blockedFromCheckout()** (1 connections) — `app/Enums/ItemStatus.php`
- **.color()** (1 connections) — `app/Enums/ItemStatus.php`
- **.needingAttention()** (1 connections) — `app/Enums/ItemStatus.php`
- **.values()** (1 connections) — `app/Enums/ItemStatus.php`

## Relationships

- [Inventory Item Resource](Inventory_Item_Resource.md) (3 shared connections)
- [Branch Scoping & Activity Log](Branch_Scoping_&_Activity_Log.md) (3 shared connections)
- [Inventory Item Model](Inventory_Item_Model.md) (3 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (2 shared connections)
- [Inventory Checkout Service](Inventory_Checkout_Service.md) (2 shared connections)
- [Inventory Category Model](Inventory_Category_Model.md) (2 shared connections)
- [Checkout Notifications & Overdue](Checkout_Notifications_&_Overdue.md) (2 shared connections)
- [Checkout Action Wiring](Checkout_Action_Wiring.md) (2 shared connections)
- [Return Status Enum](Return_Status_Enum.md) (1 shared connections)
- [Checkout Form Schema Inventory UI](Checkout_Form_Schema_Inventory_UI.md) (1 shared connections)
- [Inventory Audit Line Model](Inventory_Audit_Line_Model.md) (1 shared connections)
- [Checkout Line Model](Checkout_Line_Model.md) (1 shared connections)

## Source Files

- `app/Enums/ItemCondition.php`
- `app/Enums/ItemStatus.php`
- `app/Filament/Inventory/Actions/CheckoutFormSchema.php`
- `app/Filament/Inventory/Resources/InventoryItemResource.php`
- `app/Models/InventoryItem.php`

## Audit Trail

- EXTRACTED: 46 (98%)
- INFERRED: 1 (2%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*