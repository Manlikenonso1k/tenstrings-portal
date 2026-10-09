# Portal & LMS Controllers

> 67 nodes · cohesion 0.08

## Key Concepts

- **Illuminate\Http\Request** (67 connections)
- **Course** (54 connections) — `app/Models/Course.php`
- **Illuminate\Http\JsonResponse** (42 connections)
- **Controller** (28 connections) — `app/Http/Controllers/Controller.php`
- **web.php** (10 connections) — `routes/web.php`
- **CourseController** (9 connections) — `app/Http/Controllers/Api/V1/CourseController.php`
- **PaymentApiController** (9 connections) — `app/Http/Controllers/Api/V1/PaymentApiController.php`
- **api.php** (9 connections) — `routes/api.php`
- **AuthController** (8 connections) — `app/Http/Controllers/Api/V1/AuthController.php`
- **StudentProfileController** (8 connections) — `app/Http/Controllers/Api/V1/StudentProfileController.php`
- **AuthController.php** (7 connections) — `app/Http/Controllers/Api/V1/AuthController.php`
- **.lessons()** (7 connections) — `app/Http/Controllers/Api/V1/CourseController.php`
- **.modules()** (7 connections) — `app/Http/Controllers/Api/V1/CourseController.php`
- **PaymentApiController.php** (7 connections) — `app/Http/Controllers/Api/V1/PaymentApiController.php`
- **StudentProfileController.php** (7 connections) — `app/Http/Controllers/Api/V1/StudentProfileController.php`
- **.completeLesson()** (6 connections) — `app/Http/Controllers/Api/V1/CourseController.php`
- **.lesson()** (6 connections) — `app/Http/Controllers/Api/V1/CourseController.php`
- **GradeController** (6 connections) — `app/Http/Controllers/Api/V1/GradeController.php`
- **LessonController.php** (6 connections) — `app/Http/Controllers/Course/LessonController.php`
- **StudentProfileResource** (6 connections) — `app/Http/Resources/Api/V1/StudentProfileResource.php`
- **AnnouncementController.php** (5 connections) — `app/Http/Controllers/Api/V1/AnnouncementController.php`
- **AnnouncementController** (5 connections) — `app/Http/Controllers/Api/V1/AnnouncementController.php`
- **AttendanceController.php** (5 connections) — `app/Http/Controllers/Api/V1/AttendanceController.php`
- **AttendanceController** (5 connections) — `app/Http/Controllers/Api/V1/AttendanceController.php`
- **.login()** (5 connections) — `app/Http/Controllers/Api/V1/AuthController.php`
- *... and 42 more nodes in this community*

## Relationships

- [Payments Page & Portal Settings](Payments_Page_&_Portal_Settings.md) (29 shared connections)
- [TGIPay & Webhook Controllers](TGIPay_&_Webhook_Controllers.md) (24 shared connections)
- [Instructor Resource](Instructor_Resource.md) (10 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (6 shared connections)
- [Core Domain Models](Core_Domain_Models.md) (6 shared connections)
- [User Model & Panel Access](User_Model_&_Panel_Access.md) (6 shared connections)
- [Lesson Model](Lesson_Model.md) (5 shared connections)
- [Grade Model](Grade_Model.md) (4 shared connections)
- [Attendance Model](Attendance_Model.md) (3 shared connections)
- [Student Pdf Controller](Student_Pdf_Controller.md) (3 shared connections)
- [Student Importer](Student_Importer.md) (3 shared connections)
- [Student Portal Pages](Student_Portal_Pages.md) (2 shared connections)

## Source Files

- `app/Filament/Portal/Pages/CourseRegistrationPage.php`
- `app/Http/Controllers/Api/V1/AnnouncementController.php`
- `app/Http/Controllers/Api/V1/AttendanceController.php`
- `app/Http/Controllers/Api/V1/AuthController.php`
- `app/Http/Controllers/Api/V1/CalendarController.php`
- `app/Http/Controllers/Api/V1/CourseController.php`
- `app/Http/Controllers/Api/V1/GradeController.php`
- `app/Http/Controllers/Api/V1/PaymentApiController.php`
- `app/Http/Controllers/Api/V1/StudentProfileController.php`
- `app/Http/Controllers/Controller.php`
- `app/Http/Controllers/Course/LessonController.php`
- `app/Http/Resources/Api/V1/StudentProfileResource.php`
- `app/Models/Course.php`
- `routes/api.php`
- `routes/web.php`

## Audit Trail

- EXTRACTED: 301 (99%)
- INFERRED: 3 (1%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*