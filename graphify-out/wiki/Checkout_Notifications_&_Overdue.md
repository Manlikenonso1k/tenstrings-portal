# Checkout Notifications & Overdue

> 17 nodes · cohesion 0.17

## Key Concepts

- **InventoryCheckoutServiceTest.php** (13 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **InventoryItemReturnedBadly** (11 connections) — `app/Notifications/InventoryItemReturnedBadly.php`
- **InventoryCheckoutOverdue** (10 connections) — `app/Notifications/InventoryCheckoutOverdue.php`
- **Illuminate\Bus\Queueable** (8 connections)
- **InventoryItemReturnedBadly.php** (6 connections) — `app/Notifications/InventoryItemReturnedBadly.php`
- **MarkInventoryCheckoutsOverdue.php** (4 connections) — `app/Console/Commands/MarkInventoryCheckoutsOverdue.php`
- **InventoryCheckoutOverdue.php** (4 connections) — `app/Notifications/InventoryCheckoutOverdue.php`
- **Illuminate\Notifications\Notification** (4 connections)
- **MarkInventoryCheckoutsOverdue** (3 connections) — `app/Console/Commands/MarkInventoryCheckoutsOverdue.php`
- **.handle()** (3 connections) — `app/Console/Commands/MarkInventoryCheckoutsOverdue.php`
- **.__construct()** (3 connections) — `app/Notifications/InventoryItemReturnedBadly.php`
- **.__construct()** (2 connections) — `app/Notifications/InventoryCheckoutOverdue.php`
- **.toDatabase()** (2 connections) — `app/Notifications/InventoryItemReturnedBadly.php`
- **.toDatabase()** (1 connections) — `app/Notifications/InventoryCheckoutOverdue.php`
- **.via()** (1 connections) — `app/Notifications/InventoryCheckoutOverdue.php`
- **.via()** (1 connections) — `app/Notifications/InventoryItemReturnedBadly.php`
- **Illuminate\Support\Facades\Notification** (1 connections)

## Relationships

- [Checkout Model & Policy](Checkout_Model_&_Policy.md) (6 shared connections)
- [Inventory Checkout Service](Inventory_Checkout_Service.md) (5 shared connections)
- [Return Status Enum](Return_Status_Enum.md) (3 shared connections)
- [Inventory Item Model](Inventory_Item_Model.md) (3 shared connections)
- [Copy Sqlite To Mysql Command](Copy_Sqlite_To_Mysql_Command.md) (2 shared connections)
- [Send Whats App Message Job](Send_Whats_App_Message_Job.md) (2 shared connections)
- [Branch Student Credentials Mail](Branch_Student_Credentials_Mail.md) (2 shared connections)
- [Feature Test Harness](Feature_Test_Harness.md) (2 shared connections)
- [Item Condition & Status Enums](Item_Condition_&_Status_Enums.md) (2 shared connections)
- [Branch Model & Test Builders](Branch_Model_&_Test_Builders.md) (1 shared connections)
- [Checkout Status Enum](Checkout_Status_Enum.md) (1 shared connections)
- [Inventory Checkout Exception](Inventory_Checkout_Exception.md) (1 shared connections)

## Source Files

- `app/Console/Commands/MarkInventoryCheckoutsOverdue.php`
- `app/Notifications/InventoryCheckoutOverdue.php`
- `app/Notifications/InventoryItemReturnedBadly.php`
- `tests/Feature/InventoryCheckoutServiceTest.php`

## Audit Trail

- EXTRACTED: 54 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*