# Item Policy & Branch Access

> 16 nodes · cohesion 0.14

## Key Concepts

- **InventoryItemPolicy** (14 connections) — `app/Policies/InventoryItemPolicy.php`
- **ChecksBranchAccess** (6 connections) — `app/Policies/Concerns/ChecksBranchAccess.php`
- **.delete()** (5 connections) — `app/Policies/InventoryItemPolicy.php`
- **.forceDelete()** (4 connections) — `app/Policies/InventoryItemPolicy.php`
- **.restore()** (4 connections) — `app/Policies/InventoryItemPolicy.php`
- **InventoryItemPolicy.php** (3 connections) — `app/Policies/InventoryItemPolicy.php`
- **.dispose()** (3 connections) — `app/Policies/InventoryItemPolicy.php`
- **.transfer()** (3 connections) — `app/Policies/InventoryItemPolicy.php`
- **.update()** (3 connections) — `app/Policies/InventoryItemPolicy.php`
- **.view()** (3 connections) — `app/Policies/InventoryItemPolicy.php`
- **ChecksBranchAccess.php** (2 connections) — `app/Policies/Concerns/ChecksBranchAccess.php`
- **.sharesBranch()** (2 connections) — `app/Policies/Concerns/ChecksBranchAccess.php`
- **.create()** (2 connections) — `app/Policies/InventoryItemPolicy.php`
- **.deleteAny()** (2 connections) — `app/Policies/InventoryItemPolicy.php`
- **.viewAny()** (2 connections) — `app/Policies/InventoryItemPolicy.php`
- **.viewCost()** (2 connections) — `app/Policies/InventoryItemPolicy.php`

## Relationships

- [User Model & Panel Access](User_Model_&_Panel_Access.md) (14 shared connections)
- [Inventory Item Model](Inventory_Item_Model.md) (8 shared connections)
- [Inventory Room Model & Policy](Inventory_Room_Model_&_Policy.md) (2 shared connections)
- [Inventory Audit Model & Policy](Inventory_Audit_Model_&_Policy.md) (1 shared connections)
- [Checkout Model & Policy](Checkout_Model_&_Policy.md) (1 shared connections)

## Source Files

- `app/Policies/Concerns/ChecksBranchAccess.php`
- `app/Policies/InventoryItemPolicy.php`

## Audit Trail

- EXTRACTED: 43 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*