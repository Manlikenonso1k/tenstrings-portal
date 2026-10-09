# Movement & Photo Models

> 16 nodes · cohesion 0.19

## Key Concepts

- **Illuminate\Database\Eloquent\Relations\BelongsTo** (39 connections)
- **InventoryMovement** (10 connections) — `app/Models/InventoryMovement.php`
- **InventoryCheckoutPhoto** (5 connections) — `app/Models/InventoryCheckoutPhoto.php`
- **InventoryCheckoutPhoto.php** (4 connections) — `app/Models/InventoryCheckoutPhoto.php`
- **InventoryMovement.php** (3 connections) — `app/Models/InventoryMovement.php`
- **.course()** (2 connections) — `app/Models/CourseModule.php`
- **.branch()** (2 connections) — `app/Models/InventoryCheckout.php`
- **.releasedBy()** (2 connections) — `app/Models/InventoryCheckout.php`
- **.line()** (2 connections) — `app/Models/InventoryCheckoutPhoto.php`
- **.uploader()** (2 connections) — `app/Models/InventoryCheckoutPhoto.php`
- **.fromBranch()** (2 connections) — `app/Models/InventoryMovement.php`
- **.fromRoom()** (2 connections) — `app/Models/InventoryMovement.php`
- **.item()** (2 connections) — `app/Models/InventoryMovement.php`
- **.mover()** (2 connections) — `app/Models/InventoryMovement.php`
- **.toBranch()** (2 connections) — `app/Models/InventoryMovement.php`
- **.toRoom()** (2 connections) — `app/Models/InventoryMovement.php`

## Relationships

- [Inventory Item Model](Inventory_Item_Model.md) (7 shared connections)
- [Core Domain Models](Core_Domain_Models.md) (4 shared connections)
- [Checkout Line Model](Checkout_Line_Model.md) (4 shared connections)
- [Inventory Audit Line Model](Inventory_Audit_Line_Model.md) (3 shared connections)
- [Branch Scoping & Activity Log](Branch_Scoping_&_Activity_Log.md) (3 shared connections)
- [Inventory Audit Model & Policy](Inventory_Audit_Model_&_Policy.md) (3 shared connections)
- [Course Module Model](Course_Module_Model.md) (2 shared connections)
- [Checkout Model & Policy](Checkout_Model_&_Policy.md) (2 shared connections)
- [Certificate Model](Certificate_Model.md) (2 shared connections)
- [Lesson Model](Lesson_Model.md) (2 shared connections)
- [Inventory Checkout Service](Inventory_Checkout_Service.md) (1 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (1 shared connections)

## Source Files

- `app/Models/CourseModule.php`
- `app/Models/InventoryCheckout.php`
- `app/Models/InventoryCheckoutPhoto.php`
- `app/Models/InventoryMovement.php`

## Audit Trail

- EXTRACTED: 60 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*