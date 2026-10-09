# Student CSV Import Command

> 29 nodes · cohesion 0.14

## Key Concepts

- **.handle()** (30 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **ImportStudentsFromCsv** (26 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **Illuminate\Support\Carbon** (8 connections)
- **RuntimeException** (7 connections)
- **.findStudentByNameAndPhone()** (4 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.generateUniquePlaceholderEmail()** (4 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.notifyBranchForCredentials()** (4 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.parseOptionalDate()** (4 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.resolveStartDate()** (4 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.buildHeaderMap()** (3 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.expectedEndDate()** (3 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.exportPlaceholderEmails()** (3 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.exportSkippedRows()** (3 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.normalizePhoneForLookup()** (3 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.resolveDuration()** (3 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.generate()** (3 connections) — `app/Support/AssetTagGenerator.php`
- **.buildSkippedRow()** (2 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.cleanAndRepairEmail()** (2 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.cleanText()** (2 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.generatePassword()** (2 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.isSkippableRow()** (2 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.mapBranch()** (2 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.mapCourseName()** (2 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.normalizeHeader()** (2 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **.normalizePhone()** (2 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- *... and 4 more nodes in this community*

## Relationships

- [TGIPay & Webhook Controllers](TGIPay_&_Webhook_Controllers.md) (4 shared connections)
- [Student Model & Observer](Student_Model_&_Observer.md) (4 shared connections)
- [Instructor Resource](Instructor_Resource.md) (4 shared connections)
- [User Model & Panel Access](User_Model_&_Panel_Access.md) (2 shared connections)
- [Course Catalog Support](Course_Catalog_Support.md) (2 shared connections)
- [Student Importer](Student_Importer.md) (2 shared connections)
- [Inventory Checkout Exception](Inventory_Checkout_Exception.md) (2 shared connections)
- [Inventory Item Model](Inventory_Item_Model.md) (2 shared connections)
- [Inventory Checkout Service](Inventory_Checkout_Service.md) (2 shared connections)
- [Copy Sqlite To Mysql Command](Copy_Sqlite_To_Mysql_Command.md) (1 shared connections)
- [Enrollment Rules & Policy](Enrollment_Rules_&_Policy.md) (1 shared connections)
- [Portal & LMS Controllers](Portal_&_LMS_Controllers.md) (1 shared connections)

## Source Files

- `app/Console/Commands/ImportStudentsFromCsv.php`
- `app/Exceptions/InventoryCheckoutException.php`
- `app/Support/AssetTagGenerator.php`

## Audit Trail

- EXTRACTED: 79 (95%)
- INFERRED: 4 (5%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*