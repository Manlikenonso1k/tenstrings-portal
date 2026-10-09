# Checkout Action Wiring

> 26 nodes · cohesion 0.14

## Key Concepts

- **static** (38 connections)
- **CheckoutFormSchema** (25 connections) — `app/Filament/Inventory/Actions/CheckoutFormSchema.php`
- **CheckoutActions** (22 connections) — `app/Filament/Inventory/Actions/CheckoutActions.php`
- **Action** (5 connections)
- **.checkoutItem()** (4 connections) — `app/Filament/Inventory/Actions/CheckoutActions.php`
- **.checkoutSelected()** (4 connections) — `app/Filament/Inventory/Actions/CheckoutActions.php`
- **.newCheckout()** (4 connections) — `app/Filament/Inventory/Actions/CheckoutActions.php`
- **.returnCheckout()** (4 connections) — `app/Filament/Inventory/Actions/CheckoutActions.php`
- **.returnCheckoutHeader()** (4 connections) — `app/Filament/Inventory/Actions/CheckoutActions.php`
- **.returnItem()** (4 connections) — `app/Filament/Inventory/Actions/CheckoutActions.php`
- **.table()** (4 connections) — `app/Filament/Inventory/Resources/InventoryItemResource.php`
- **.runReturn()** (3 connections) — `app/Filament/Inventory/Actions/CheckoutActions.php`
- **.outstandingLineOptions()** (3 connections) — `app/Filament/Inventory/Actions/CheckoutFormSchema.php`
- **.photoUpload()** (3 connections) — `app/Filament/Inventory/Actions/CheckoutFormSchema.php`
- **.returnQuantityFields()** (3 connections) — `app/Filament/Inventory/Actions/CheckoutFormSchema.php`
- **.returnSteps()** (3 connections) — `app/Filament/Inventory/Actions/CheckoutFormSchema.php`
- **.runCheckout()** (2 connections) — `app/Filament/Inventory/Actions/CheckoutActions.php`
- **.availableItemOptions()** (2 connections) — `app/Filament/Inventory/Actions/CheckoutFormSchema.php`
- **.checkoutSteps()** (2 connections) — `app/Filament/Inventory/Actions/CheckoutFormSchema.php`
- **.findItem()** (2 connections) — `app/Filament/Inventory/Actions/CheckoutFormSchema.php`
- **.booted()** (2 connections) — `app/Models/Course.php`
- **.photoMinimum()** (1 connections) — `app/Filament/Inventory/Actions/CheckoutFormSchema.php`
- **.toCheckoutPayload()** (1 connections) — `app/Filament/Inventory/Actions/CheckoutFormSchema.php`
- **.toReturnPayload()** (1 connections) — `app/Filament/Inventory/Actions/CheckoutFormSchema.php`
- **FileUpload** (1 connections)
- *... and 1 more nodes in this community*

## Relationships

- [Checkout Form Schema Inventory UI](Checkout_Form_Schema_Inventory_UI.md) (6 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (5 shared connections)
- [Checkout Model & Policy](Checkout_Model_&_Policy.md) (5 shared connections)
- [Inventory Item Resource](Inventory_Item_Resource.md) (3 shared connections)
- [Inventory Item Model](Inventory_Item_Model.md) (3 shared connections)
- [Checkout Resource & Page Tests](Checkout_Resource_&_Page_Tests.md) (2 shared connections)
- [List Inventory Checkouts Inventory UI](List_Inventory_Checkouts_Inventory_UI.md) (2 shared connections)
- [Inventory View Pages](Inventory_View_Pages.md) (2 shared connections)
- [Item Condition & Status Enums](Item_Condition_&_Status_Enums.md) (2 shared connections)
- [Return Status Enum](Return_Status_Enum.md) (2 shared connections)
- [User Factory](User_Factory.md) (2 shared connections)
- [Inventory Room Resource](Inventory_Room_Resource.md) (2 shared connections)

## Source Files

- `app/Filament/Inventory/Actions/CheckoutActions.php`
- `app/Filament/Inventory/Actions/CheckoutFormSchema.php`
- `app/Filament/Inventory/Resources/InventoryItemResource.php`
- `app/Models/Course.php`

## Audit Trail

- EXTRACTED: 57 (56%)
- INFERRED: 44 (44%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*