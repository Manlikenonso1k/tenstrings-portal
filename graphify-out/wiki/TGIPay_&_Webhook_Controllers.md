# TGIPay & Webhook Controllers

> 46 nodes · cohesion 0.08

## Key Concepts

- **Payment** (49 connections) — `app/Models/Payment.php`
- **PaymentService** (28 connections) — `app/Services/Payments/PaymentService.php`
- **ViewStudent.php** (21 connections) — `app/Filament/Resources/StudentResource/Pages/ViewStudent.php`
- **Invoice** (18 connections) — `app/Models/Invoice.php`
- **ImportStudentsFromCsv.php** (15 connections) — `app/Console/Commands/ImportStudentsFromCsv.php`
- **PaymentService.php** (12 connections) — `app/Services/Payments/PaymentService.php`
- **TgiPayController.php** (11 connections) — `app/Http/Controllers/TgiPayController.php`
- **Illuminate\Support\Facades\Hash** (11 connections)
- **Illuminate\Support\Facades\Log** (10 connections)
- **.handleWebhook()** (9 connections) — `app/Services/Payments/PaymentService.php`
- **DocumentService** (8 connections) — `app/Services/Payments/DocumentService.php`
- **TgiPayController** (7 connections) — `app/Http/Controllers/TgiPayController.php`
- **StudentObserver.php** (7 connections) — `app/Observers/StudentObserver.php`
- **Illuminate\Support\Facades\Storage** (7 connections)
- **.initiatePayment()** (6 connections) — `app/Http/Controllers/TgiPayController.php`
- **WebhookController** (6 connections) — `app/Http/Controllers/WebhookController.php`
- **.gateway()** (6 connections) — `app/Services/Payments/PaymentService.php`
- **.reconcileTgiPayPayment()** (6 connections) — `app/Services/Payments/PaymentService.php`
- **WebhookController.php** (5 connections) — `app/Http/Controllers/WebhookController.php`
- **DocumentService.php** (5 connections) — `app/Services/Payments/DocumentService.php`
- **.initializePayment()** (5 connections) — `app/Services/Payments/PaymentService.php`
- **.syncCourseFeeAndStudentSnapshot()** (5 connections) — `app/Services/Payments/PaymentService.php`
- **Throwable** (5 connections)
- **.callback()** (4 connections) — `app/Http/Controllers/TgiPayController.php`
- **.markAdviceAsPaid()** (4 connections) — `app/Services/Payments/PaymentService.php`
- *... and 21 more nodes in this community*

## Relationships

- [Payments Page & Portal Settings](Payments_Page_&_Portal_Settings.md) (32 shared connections)
- [Portal & LMS Controllers](Portal_&_LMS_Controllers.md) (24 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (13 shared connections)
- [Instructor Resource](Instructor_Resource.md) (8 shared connections)
- [Student Model & Observer](Student_Model_&_Observer.md) (8 shared connections)
- [User Model & Panel Access](User_Model_&_Panel_Access.md) (7 shared connections)
- [Payment Gateway Contracts](Payment_Gateway_Contracts.md) (6 shared connections)
- [Core Domain Models](Core_Domain_Models.md) (5 shared connections)
- [Student CSV Import Command](Student_CSV_Import_Command.md) (4 shared connections)
- [Ajah Inventory Importer](Ajah_Inventory_Importer.md) (4 shared connections)
- [Import Students Csv Admin Page](Import_Students_Csv_Admin_Page.md) (3 shared connections)
- [Branch Scoping & Activity Log](Branch_Scoping_&_Activity_Log.md) (3 shared connections)

## Source Files

- `app/Console/Commands/ImportStudentsFromCsv.php`
- `app/Filament/Resources/StudentResource/Pages/ViewStudent.php`
- `app/Http/Controllers/PaymentController.php`
- `app/Http/Controllers/TgiPayController.php`
- `app/Http/Controllers/WebhookController.php`
- `app/Models/Invoice.php`
- `app/Models/Payment.php`
- `app/Observers/StudentObserver.php`
- `app/Services/Payments/DocumentService.php`
- `app/Services/Payments/PaymentService.php`

## Audit Trail

- EXTRACTED: 227 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*