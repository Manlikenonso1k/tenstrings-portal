# Inventory Audit Line Model

> 10 nodes · cohesion 0.22

## Key Concepts

- **InventoryAuditLine** (8 connections) — `app/Models/InventoryAuditLine.php`
- **CreateInventoryAudit.php** (5 connections) — `app/Filament/Inventory/Resources/InventoryAuditResource/Pages/CreateInventoryAudit.php`
- **CreateInventoryAudit** (4 connections) — `app/Filament/Inventory/Resources/InventoryAuditResource/Pages/CreateInventoryAudit.php`
- **InventoryAuditLine.php** (4 connections) — `app/Models/InventoryAuditLine.php`
- **.afterCreate()** (3 connections) — `app/Filament/Inventory/Resources/InventoryAuditResource/Pages/CreateInventoryAudit.php`
- **.audit()** (2 connections) — `app/Models/InventoryAuditLine.php`
- **.item()** (2 connections) — `app/Models/InventoryAuditLine.php`
- **.mutateFormDataBeforeCreate()** (1 connections) — `app/Filament/Inventory/Resources/InventoryAuditResource/Pages/CreateInventoryAudit.php`
- **.hasVariance()** (1 connections) — `app/Models/InventoryAuditLine.php`
- **.variance()** (1 connections) — `app/Models/InventoryAuditLine.php`

## Relationships

- [Movement & Photo Models](Movement_&_Photo_Models.md) (3 shared connections)
- [Inventory Item Model](Inventory_Item_Model.md) (2 shared connections)
- [Resource Create Pages](Resource_Create_Pages.md) (2 shared connections)
- [Core Domain Models](Core_Domain_Models.md) (2 shared connections)
- [Inventory Audit Resource Inventory UI](Inventory_Audit_Resource_Inventory_UI.md) (1 shared connections)
- [Item Condition & Status Enums](Item_Condition_&_Status_Enums.md) (1 shared connections)

## Source Files

- `app/Filament/Inventory/Resources/InventoryAuditResource/Pages/CreateInventoryAudit.php`
- `app/Models/InventoryAuditLine.php`

## Audit Trail

- EXTRACTED: 21 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*