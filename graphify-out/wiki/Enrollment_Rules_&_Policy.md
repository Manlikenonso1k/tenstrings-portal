# Enrollment Rules & Policy

> 18 nodes · cohesion 0.15

## Key Concepts

- **Enrollment** (26 connections) — `app/Models/Enrollment.php`
- **EnrollmentLimitService** (8 connections) — `app/Support/EnrollmentLimitService.php`
- **EnrollmentPolicy** (6 connections) — `app/Policies/EnrollmentPolicy.php`
- **.test_student_cannot_exceed_two_ongoing_courses()** (5 connections) — `tests/Feature/EnrollmentLimitTest.php`
- **.registerCourses()** (4 connections) — `app/Filament/Portal/Pages/CourseRegistrationPage.php`
- **EnrollmentPolicy.php** (3 connections) — `app/Policies/EnrollmentPolicy.php`
- **.delete()** (3 connections) — `app/Policies/EnrollmentPolicy.php`
- **.update()** (3 connections) — `app/Policies/EnrollmentPolicy.php`
- **.view()** (3 connections) — `app/Policies/EnrollmentPolicy.php`
- **.getViewData()** (2 connections) — `app/Filament/Portal/Pages/CourseRegistrationPage.php`
- **.validateMaxTwoCourses()** (2 connections) — `app/Filament/Resources/EnrollmentResource.php`
- **.booted()** (2 connections) — `app/Models/Enrollment.php`
- **.create()** (2 connections) — `app/Policies/EnrollmentPolicy.php`
- **.viewAny()** (2 connections) — `app/Policies/EnrollmentPolicy.php`
- **EnrollmentLimitService.php** (2 connections) — `app/Support/EnrollmentLimitService.php`
- **.canEnrollInCourses()** (2 connections) — `app/Support/EnrollmentLimitService.php`
- **.courses()** (1 connections) — `app/Models/Enrollment.php`
- **.student()** (1 connections) — `app/Models/Enrollment.php`

## Relationships

- [User Model & Panel Access](User_Model_&_Panel_Access.md) (6 shared connections)
- [Student Portal Pages](Student_Portal_Pages.md) (4 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (4 shared connections)
- [Feature Test Harness](Feature_Test_Harness.md) (3 shared connections)
- [Core Domain Models](Core_Domain_Models.md) (3 shared connections)
- [Portal & LMS Controllers](Portal_&_LMS_Controllers.md) (2 shared connections)
- [Instructor Resource](Instructor_Resource.md) (2 shared connections)
- [Enrollment Resource Admin UI](Enrollment_Resource_Admin_UI.md) (1 shared connections)
- [Student CSV Import Command](Student_CSV_Import_Command.md) (1 shared connections)
- [Student Importer](Student_Importer.md) (1 shared connections)
- [TGIPay & Webhook Controllers](TGIPay_&_Webhook_Controllers.md) (1 shared connections)
- [Ajah Inventory Importer](Ajah_Inventory_Importer.md) (1 shared connections)

## Source Files

- `app/Filament/Portal/Pages/CourseRegistrationPage.php`
- `app/Filament/Resources/EnrollmentResource.php`
- `app/Models/Enrollment.php`
- `app/Policies/EnrollmentPolicy.php`
- `app/Support/EnrollmentLimitService.php`
- `tests/Feature/EnrollmentLimitTest.php`

## Audit Trail

- EXTRACTED: 53 (98%)
- INFERRED: 1 (2%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*