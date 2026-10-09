# Course

> God node · 54 connections · `app/Models/Course.php`

**Community:** [Portal & LMS Controllers](Portal_&_LMS_Controllers.md)

## Connections by Relation

### calls
- .handle() `EXTRACTED`
- .store() `EXTRACTED`
- .getHeaderActions() `EXTRACTED`
- .beforeSave() `EXTRACTED`
- .afterCreate() `EXTRACTED`
- .syncFinancialRecords() `EXTRACTED`
- .createStudentCourseFee() `EXTRACTED`
- .test_student_cannot_exceed_two_ongoing_courses() `EXTRACTED`
- .registerCourses() `EXTRACTED`
- .index() `EXTRACTED`
- .form() `EXTRACTED`
- .getStats() `EXTRACTED`
- .getComputedCourseFeeAttribute() `INFERRED`
- .getViewData() `EXTRACTED`
- .allCourses() `EXTRACTED`
- .run() `EXTRACTED`

### contains
- Course.php `EXTRACTED`

### imports
- ViewStudent.php `EXTRACTED`
- StudentRegistrationController.php `EXTRACTED`
- StudentImporter.php `EXTRACTED`
- ImportStudentsFromCsv.php `EXTRACTED`
- CourseRegistrationPage.php `EXTRACTED`
- CourseResource.php `EXTRACTED`
- StudentObserver.php `EXTRACTED`
- EnrollmentLimitTest.php `EXTRACTED`
- LessonController.php `EXTRACTED`
- CourseController.php `EXTRACTED`
- SchoolStatsOverview.php `EXTRACTED`
- CoursesPage.php `EXTRACTED`
- CoursePolicy.php `EXTRACTED`
- CourseSeeder.php `EXTRACTED`
- FeeCalculationService.php `EXTRACTED`
- CourseCatalog.php `EXTRACTED`

### inherits
- Illuminate\Database\Eloquent\Model `EXTRACTED`

### method
- .booted() `EXTRACTED`
- .modules() `EXTRACTED`
- .instructors() `EXTRACTED`
- .enrollments() `EXTRACTED`
- .getBaseFeeAttribute() `EXTRACTED`

### mixes_in
- Illuminate\Database\Eloquent\Factories\HasFactory `EXTRACTED`

### references
- .lessons() `EXTRACTED`
- .modules() `EXTRACTED`
- .completeLesson() `EXTRACTED`
- .lesson() `EXTRACTED`
- .show() `EXTRACTED`
- .show() `EXTRACTED`
- .index() `EXTRACTED`
- .unlockedModuleIdsForCourse() `EXTRACTED`
- .delete() `EXTRACTED`
- .update() `EXTRACTED`
- .view() `EXTRACTED`
- .effectiveFeeFor() `EXTRACTED`
- .calculateRequiredAmount() `EXTRACTED`
- .getRequiredPercentage() `EXTRACTED`

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*