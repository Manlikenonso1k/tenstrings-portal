# Branch Scoping & Activity Log

> 23 nodes · cohesion 0.13

## Key Concepts

- **Illuminate\Database\Eloquent\Builder** (27 connections)
- **InventoryItem.php** (13 connections) — `app/Models/InventoryItem.php`
- **ScopedToBranch** (11 connections) — `app/Models/Concerns/ScopedToBranch.php`
- **InventoryCheckout.php** (10 connections) — `app/Models/InventoryCheckout.php`
- **Spatie\Activitylog\LogOptions** (10 connections)
- **Spatie\Activitylog\Traits\LogsActivity** (10 connections)
- **InventoryRoom.php** (9 connections) — `app/Models/InventoryRoom.php`
- **Student.php** (8 connections) — `app/Models/Student.php`
- **Payment.php** (6 connections) — `app/Models/Payment.php`
- **Illuminate\Database\Eloquent\SoftDeletes** (6 connections)
- **ScopedToBranch.php** (3 connections) — `app/Models/Concerns/ScopedToBranch.php`
- **.acrossAllBranches()** (3 connections) — `app/Models/Concerns/ScopedToBranch.php`
- **.getActivitylogOptions()** (3 connections) — `app/Models/InventoryCheckout.php`
- **.getActivitylogOptions()** (3 connections) — `app/Models/InventoryItem.php`
- **.scopeNotVerifiedSince()** (3 connections) — `app/Models/InventoryItem.php`
- **.getActivitylogOptions()** (3 connections) — `app/Models/Payment.php`
- **.getActivitylogOptions()** (3 connections) — `app/Models/Student.php`
- **.bootScopedToBranch()** (2 connections) — `app/Models/Concerns/ScopedToBranch.php`
- **LogOptions** (1 connections)
- **LogOptions** (1 connections)
- **LogOptions** (1 connections)
- **LogOptions** (1 connections)
- **DateTimeInterface** (1 connections)

## Relationships

- [Core Domain Models](Core_Domain_Models.md) (7 shared connections)
- [Branch Enrollment Doughnut Dashboard Widget (2)](Branch_Enrollment_Doughnut_Dashboard_Widget_2.md) (7 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (6 shared connections)
- [Checkout Model & Policy](Checkout_Model_&_Policy.md) (6 shared connections)
- [Inventory Item Model](Inventory_Item_Model.md) (6 shared connections)
- [Inventory Room Model & Policy](Inventory_Room_Model_&_Policy.md) (5 shared connections)
- [Student Model & Observer](Student_Model_&_Observer.md) (5 shared connections)
- [Checkout Line Model](Checkout_Line_Model.md) (3 shared connections)
- [Checkout Status Enum](Checkout_Status_Enum.md) (3 shared connections)
- [Movement & Photo Models](Movement_&_Photo_Models.md) (3 shared connections)
- [Item Condition & Status Enums](Item_Condition_&_Status_Enums.md) (3 shared connections)
- [TGIPay & Webhook Controllers](TGIPay_&_Webhook_Controllers.md) (3 shared connections)

## Source Files

- `app/Models/Concerns/ScopedToBranch.php`
- `app/Models/InventoryCheckout.php`
- `app/Models/InventoryItem.php`
- `app/Models/InventoryRoom.php`
- `app/Models/Payment.php`
- `app/Models/Student.php`

## Audit Trail

- EXTRACTED: 102 (98%)
- INFERRED: 2 (2%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*