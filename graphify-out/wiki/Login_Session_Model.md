# Login Session Model

> 12 nodes · cohesion 0.23

## Key Concepts

- **LoginSession** (12 connections) — `app/Models/LoginSession.php`
- **StoreLoginSession.php** (4 connections) — `app/Listeners/StoreLoginSession.php`
- **StoreLogoutSession.php** (4 connections) — `app/Listeners/StoreLogoutSession.php`
- **StoreLoginSession** (3 connections) — `app/Listeners/StoreLoginSession.php`
- **.handle()** (3 connections) — `app/Listeners/StoreLoginSession.php`
- **StoreLogoutSession** (3 connections) — `app/Listeners/StoreLogoutSession.php`
- **.handle()** (3 connections) — `app/Listeners/StoreLogoutSession.php`
- **LoginSession.php** (3 connections) — `app/Models/LoginSession.php`
- **Illuminate\Auth\Events\Login** (3 connections)
- **Illuminate\Auth\Events\Logout** (3 connections)
- **.getLastSeenLabelAttribute()** (1 connections) — `app/Models/LoginSession.php`
- **.user()** (1 connections) — `app/Models/LoginSession.php`

## Relationships

- [Student Model & Observer](Student_Model_&_Observer.md) (4 shared connections)
- [Core Domain Models](Core_Domain_Models.md) (4 shared connections)
- [User Model & Panel Access](User_Model_&_Panel_Access.md) (2 shared connections)
- [System Activity Line Chart Dashboard Widget](System_Activity_Line_Chart_Dashboard_Widget.md) (1 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (1 shared connections)
- [Branch Enrollment Doughnut Dashboard Widget (2)](Branch_Enrollment_Doughnut_Dashboard_Widget_2.md) (1 shared connections)

## Source Files

- `app/Listeners/StoreLoginSession.php`
- `app/Listeners/StoreLogoutSession.php`
- `app/Models/LoginSession.php`

## Audit Trail

- EXTRACTED: 28 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*