# Lesson Model

> 13 nodes · cohesion 0.21

## Key Concepts

- **Lesson** (10 connections) — `app/Models/Lesson.php`
- **EnsureLessonModuleIsUnlocked.php** (5 connections) — `app/Http/Middleware/EnsureLessonModuleIsUnlocked.php`
- **Lesson.php** (5 connections) — `app/Models/Lesson.php`
- **.completedLessons()** (5 connections) — `app/Models/User.php`
- **.handle()** (4 connections) — `app/Http/Middleware/EnsureLessonModuleIsUnlocked.php`
- **Illuminate\Database\Eloquent\Relations\BelongsToMany** (4 connections)
- **.hasCompletedAllLessonsInModule()** (3 connections) — `app/Models/User.php`
- **.unlockedModuleIdsForCourse()** (3 connections) — `app/Models/User.php`
- **EnsureLessonModuleIsUnlocked** (2 connections) — `app/Http/Middleware/EnsureLessonModuleIsUnlocked.php`
- **.completedByUsers()** (2 connections) — `app/Models/Lesson.php`
- **.module()** (2 connections) — `app/Models/Lesson.php`
- **Closure** (2 connections)
- **Symfony\Component\HttpFoundation\Response** (2 connections)

## Relationships

- [Portal & LMS Controllers](Portal_&_LMS_Controllers.md) (5 shared connections)
- [User Model & Panel Access](User_Model_&_Panel_Access.md) (5 shared connections)
- [Core Domain Models](Core_Domain_Models.md) (4 shared connections)
- [Movement & Photo Models](Movement_&_Photo_Models.md) (2 shared connections)
- [Course Module Model](Course_Module_Model.md) (1 shared connections)

## Source Files

- `app/Http/Middleware/EnsureLessonModuleIsUnlocked.php`
- `app/Models/Lesson.php`
- `app/Models/User.php`

## Audit Trail

- EXTRACTED: 33 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*