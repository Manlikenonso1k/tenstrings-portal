# Student Model & Observer

> 26 nodes · cohesion 0.11

## Key Concepts

- **Student** (91 connections) — `app/Models/Student.php`
- **EventServiceProvider.php** (7 connections) — `app/Providers/EventServiceProvider.php`
- **StudentObserver** (6 connections) — `app/Observers/StudentObserver.php`
- **.createStudentCourseFee()** (6 connections) — `app/Observers/StudentObserver.php`
- **.createUserAccount()** (5 connections) — `app/Observers/StudentObserver.php`
- **.booted()** (4 connections) — `app/Models/Student.php`
- **.created()** (4 connections) — `app/Observers/StudentObserver.php`
- **MatricNumberGenerator** (4 connections) — `app/Support/MatricNumberGenerator.php`
- **.sendBranchCredentials()** (4 connections) — `app/Support/StudentMatricMailer.php`
- **.updated()** (3 connections) — `app/Observers/StudentObserver.php`
- **EventServiceProvider** (3 connections) — `app/Providers/EventServiceProvider.php`
- **MatricNumberGenerator.php** (3 connections) — `app/Support/MatricNumberGenerator.php`
- **.generate()** (3 connections) — `app/Support/MatricNumberGenerator.php`
- **.send()** (3 connections) — `app/Support/StudentMatricMailer.php`
- **.scopeMainIntakeMonths()** (2 connections) — `app/Models/Student.php`
- **.user()** (2 connections) — `app/Models/Student.php`
- **.boot()** (2 connections) — `app/Providers/EventServiceProvider.php`
- **.sendWithCredentials()** (2 connections) — `app/Support/StudentMatricMailer.php`
- **.attendances()** (1 connections) — `app/Models/Student.php`
- **.enrollments()** (1 connections) — `app/Models/Student.php`
- **.getFullNameAttribute()** (1 connections) — `app/Models/Student.php`
- **.grades()** (1 connections) — `app/Models/Student.php`
- **.invoices()** (1 connections) — `app/Models/Student.php`
- **.payments()** (1 connections) — `app/Models/Student.php`
- **Carbon\Carbon** (1 connections)
- *... and 1 more nodes in this community*

## Relationships

- [TGIPay & Webhook Controllers](TGIPay_&_Webhook_Controllers.md) (8 shared connections)
- [Instructor Resource](Instructor_Resource.md) (7 shared connections)
- [Payments Page & Portal Settings](Payments_Page_&_Portal_Settings.md) (5 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (5 shared connections)
- [Branch Student Credentials Mail](Branch_Student_Credentials_Mail.md) (5 shared connections)
- [Branch Enrollment Doughnut Dashboard Widget (2)](Branch_Enrollment_Doughnut_Dashboard_Widget_2.md) (5 shared connections)
- [User Model & Panel Access](User_Model_&_Panel_Access.md) (5 shared connections)
- [Branch Scoping & Activity Log](Branch_Scoping_&_Activity_Log.md) (5 shared connections)
- [Student CSV Import Command](Student_CSV_Import_Command.md) (4 shared connections)
- [Instructor & Course Seeders](Instructor_&_Course_Seeders.md) (4 shared connections)
- [Student Pdf Controller](Student_Pdf_Controller.md) (4 shared connections)
- [Login Session Model](Login_Session_Model.md) (4 shared connections)

## Source Files

- `app/Models/Student.php`
- `app/Observers/StudentObserver.php`
- `app/Providers/EventServiceProvider.php`
- `app/Support/MatricNumberGenerator.php`
- `app/Support/StudentMatricMailer.php`

## Audit Trail

- EXTRACTED: 122 (98%)
- INFERRED: 3 (2%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*