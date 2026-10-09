# Certificate Model

> 8 nodes · cohesion 0.21

## Key Concepts

- **Certificate** (6 connections) — `app/Models/Certificate.php`
- **InventoryTransfer** (5 connections) — `app/Models/InventoryTransfer.php`
- **Certificate.php** (3 connections) — `app/Models/Certificate.php`
- **InventoryTransfer.php** (3 connections) — `app/Models/InventoryTransfer.php`
- **.booted()** (2 connections) — `app/Models/Certificate.php`
- **.item()** (2 connections) — `app/Models/InventoryTransfer.php`
- **.course()** (1 connections) — `app/Models/Certificate.php`
- **.student()** (1 connections) — `app/Models/Certificate.php`

## Relationships

- [Core Domain Models](Core_Domain_Models.md) (6 shared connections)
- [Movement & Photo Models](Movement_&_Photo_Models.md) (2 shared connections)
- [Checkout Action Wiring](Checkout_Action_Wiring.md) (1 shared connections)
- [Inventory Item Resource](Inventory_Item_Resource.md) (1 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (1 shared connections)

## Source Files

- `app/Models/Certificate.php`
- `app/Models/InventoryTransfer.php`

## Audit Trail

- EXTRACTED: 16 (94%)
- INFERRED: 1 (6%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*