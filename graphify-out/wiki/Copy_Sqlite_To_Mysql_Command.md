# Copy Sqlite To Mysql Command

> 12 nodes · cohesion 0.24

## Key Concepts

- **Illuminate\Console\Command** (12 connections)
- **CopySqliteToMysql** (6 connections) — `app/Console/Commands/CopySqliteToMysql.php`
- **RelocateInventoryPhotos.php** (5 connections) — `app/Console/Commands/RelocateInventoryPhotos.php`
- **CopySqliteToMysql.php** (4 connections) — `app/Console/Commands/CopySqliteToMysql.php`
- **AuditStudentsCsvImport.php** (3 connections) — `app/Console/Commands/AuditStudentsCsvImport.php`
- **.copyTable()** (3 connections) — `app/Console/Commands/CopySqliteToMysql.php`
- **.normalizeRowForMysql()** (3 connections) — `app/Console/Commands/CopySqliteToMysql.php`
- **RelocateInventoryPhotos** (3 connections) — `app/Console/Commands/RelocateInventoryPhotos.php`
- **.buildUniqueReplacementEmail()** (2 connections) — `app/Console/Commands/CopySqliteToMysql.php`
- **.handle()** (2 connections) — `app/Console/Commands/CopySqliteToMysql.php`
- **.handle()** (1 connections) — `app/Console/Commands/RelocateInventoryPhotos.php`
- **Illuminate\Support\Facades\Config** (1 connections)

## Relationships

- [Audit Students Csv Import Command](Audit_Students_Csv_Import_Command.md) (2 shared connections)
- [TGIPay & Webhook Controllers](TGIPay_&_Webhook_Controllers.md) (2 shared connections)
- [Checkout Notifications & Overdue](Checkout_Notifications_&_Overdue.md) (2 shared connections)
- [Send Whats App Message Job](Send_Whats_App_Message_Job.md) (2 shared connections)
- [Student Model & Observer](Student_Model_&_Observer.md) (1 shared connections)
- [Branch Enrollment Doughnut Dashboard Widget (2)](Branch_Enrollment_Doughnut_Dashboard_Widget_2.md) (1 shared connections)
- [Inventory Item Model](Inventory_Item_Model.md) (1 shared connections)
- [Inventory Room Model & Policy](Inventory_Room_Model_&_Policy.md) (1 shared connections)
- [Student CSV Import Command](Student_CSV_Import_Command.md) (1 shared connections)

## Source Files

- `app/Console/Commands/AuditStudentsCsvImport.php`
- `app/Console/Commands/CopySqliteToMysql.php`
- `app/Console/Commands/RelocateInventoryPhotos.php`

## Audit Trail

- EXTRACTED: 29 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*