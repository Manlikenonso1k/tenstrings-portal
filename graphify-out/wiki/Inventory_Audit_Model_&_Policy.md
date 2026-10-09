# Inventory Audit Model & Policy

> 17 nodes · cohesion 0.15

## Key Concepts

- **InventoryAudit** (16 connections) — `app/Models/InventoryAudit.php`
- **InventoryAuditPolicy** (10 connections) — `app/Policies/InventoryAuditPolicy.php`
- **InventoryAuditPolicy.php** (3 connections) — `app/Policies/InventoryAuditPolicy.php`
- **.complete()** (3 connections) — `app/Policies/InventoryAuditPolicy.php`
- **.delete()** (3 connections) — `app/Policies/InventoryAuditPolicy.php`
- **.update()** (3 connections) — `app/Policies/InventoryAuditPolicy.php`
- **.view()** (3 connections) — `app/Policies/InventoryAuditPolicy.php`
- **.branch()** (2 connections) — `app/Models/InventoryAudit.php`
- **.conductor()** (2 connections) — `app/Models/InventoryAudit.php`
- **.lines()** (2 connections) — `app/Models/InventoryAudit.php`
- **.room()** (2 connections) — `app/Models/InventoryAudit.php`
- **.variances()** (2 connections) — `app/Models/InventoryAudit.php`
- **.create()** (2 connections) — `app/Policies/InventoryAuditPolicy.php`
- **.deleteAny()** (2 connections) — `app/Policies/InventoryAuditPolicy.php`
- **.viewAny()** (2 connections) — `app/Policies/InventoryAuditPolicy.php`
- **.isEditable()** (1 connections) — `app/Models/InventoryAudit.php`
- **Collection** (1 connections)

## Relationships

- [User Model & Panel Access](User_Model_&_Panel_Access.md) (8 shared connections)
- [Movement & Photo Models](Movement_&_Photo_Models.md) (3 shared connections)
- [Inventory Room Model & Policy](Inventory_Room_Model_&_Policy.md) (2 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (1 shared connections)
- [Inventory Audit Resource Inventory UI](Inventory_Audit_Resource_Inventory_UI.md) (1 shared connections)
- [Core Domain Models](Core_Domain_Models.md) (1 shared connections)
- [Branch Scoping & Activity Log](Branch_Scoping_&_Activity_Log.md) (1 shared connections)
- [Checkout Line Model](Checkout_Line_Model.md) (1 shared connections)
- [Item Policy & Branch Access](Item_Policy_&_Branch_Access.md) (1 shared connections)

## Source Files

- `app/Models/InventoryAudit.php`
- `app/Policies/InventoryAuditPolicy.php`

## Audit Trail

- EXTRACTED: 39 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*