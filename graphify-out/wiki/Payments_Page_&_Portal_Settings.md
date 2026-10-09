# Payments Page & Portal Settings

> 53 nodes · cohesion 0.07

## Key Concepts

- **StudentCourseFee** (27 connections) — `app/Models/StudentCourseFee.php`
- **PaymentAdvice** (18 connections) — `app/Models/PaymentAdvice.php`
- **PortalSetting** (17 connections) — `app/Models/PortalSetting.php`
- **PaymentController.php** (16 connections) — `app/Http/Controllers/PaymentController.php`
- **FeeWorkflowController** (16 connections) — `app/Http/Controllers/Portal/FeeWorkflowController.php`
- **Illuminate\Http\RedirectResponse** (15 connections)
- **PaymentController** (13 connections) — `app/Http/Controllers/PaymentController.php`
- **PortalSettingResource** (11 connections) — `app/Filament/Resources/PortalSettingResource.php`
- **FeeWorkflowController.php** (11 connections) — `app/Http/Controllers/Portal/FeeWorkflowController.php`
- **PaymentsPage.php** (10 connections) — `app/Filament/Portal/Pages/PaymentsPage.php`
- **.paymentStep()** (9 connections) — `app/Http/Controllers/Portal/FeeWorkflowController.php`
- **.submitPayment()** (8 connections) — `app/Http/Controllers/Portal/FeeWorkflowController.php`
- **.resetStudentPayment()** (7 connections) — `app/Http/Controllers/PaymentController.php`
- **Illuminate\View\View** (7 connections)
- **.getViewData()** (6 connections) — `app/Filament/Portal/Pages/PaymentsPage.php`
- **.generateAdvice()** (6 connections) — `app/Http/Controllers/Portal/FeeWorkflowController.php`
- **FeeCalculationService** (6 connections) — `app/Services/Payments/FeeCalculationService.php`
- **.callback()** (5 connections) — `app/Http/Controllers/PaymentController.php`
- **.payOutstanding()** (5 connections) — `app/Http/Controllers/PaymentController.php`
- **.currentAdvice()** (5 connections) — `app/Http/Controllers/Portal/FeeWorkflowController.php`
- **.generatePage()** (5 connections) — `app/Http/Controllers/Portal/FeeWorkflowController.php`
- **.receipts()** (5 connections) — `app/Http/Controllers/Portal/FeeWorkflowController.php`
- **PaymentsPage** (4 connections) — `app/Filament/Portal/Pages/PaymentsPage.php`
- **.feeStatus()** (4 connections) — `app/Http/Controllers/Api/V1/PaymentApiController.php`
- **.downloadStudentReceipt()** (4 connections) — `app/Http/Controllers/PaymentController.php`
- *... and 28 more nodes in this community*

## Relationships

- [TGIPay & Webhook Controllers](TGIPay_&_Webhook_Controllers.md) (32 shared connections)
- [Portal & LMS Controllers](Portal_&_LMS_Controllers.md) (29 shared connections)
- [Core Domain Models](Core_Domain_Models.md) (9 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (8 shared connections)
- [Student Model & Observer](Student_Model_&_Observer.md) (5 shared connections)
- [Instructor Resource](Instructor_Resource.md) (5 shared connections)
- [Courses Page Portal UI](Courses_Page_Portal_UI.md) (2 shared connections)
- [Ajah Inventory Importer](Ajah_Inventory_Importer.md) (2 shared connections)
- [Filament Panel Providers](Filament_Panel_Providers.md) (1 shared connections)
- [Resource Create Pages](Resource_Create_Pages.md) (1 shared connections)
- [Inventory Edit & List Pages](Inventory_Edit_&_List_Pages.md) (1 shared connections)
- [Admin Resource List Pages](Admin_Resource_List_Pages.md) (1 shared connections)

## Source Files

- `app/Filament/Portal/Pages/PaymentsPage.php`
- `app/Filament/Resources/PortalSettingResource.php`
- `app/Http/Controllers/Api/V1/PaymentApiController.php`
- `app/Http/Controllers/PaymentController.php`
- `app/Http/Controllers/Portal/FeeWorkflowController.php`
- `app/Models/Payment.php`
- `app/Models/PaymentAdvice.php`
- `app/Models/PortalSetting.php`
- `app/Models/StudentCourseFee.php`
- `app/Services/Payments/FeeCalculationService.php`

## Audit Trail

- EXTRACTED: 199 (99%)
- INFERRED: 2 (1%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*