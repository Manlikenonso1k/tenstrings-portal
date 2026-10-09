# Student Login Portal UI

> 9 nodes · cohesion 0.31

## Key Concepts

- **StudentLogin** (5 connections) — `app/Filament/Portal/Pages/Auth/StudentLogin.php`
- **AdminLogin** (4 connections) — `app/Filament/Pages/Auth/AdminLogin.php`
- **StudentLogin.php** (4 connections) — `app/Filament/Portal/Pages/Auth/StudentLogin.php`
- **Filament\Pages\Auth\Login** (4 connections)
- **.getEmailFormComponent()** (3 connections) — `app/Filament/Portal/Pages/Auth/StudentLogin.php`
- **Filament\Forms\Components\TextInput** (3 connections)
- **AdminLogin.php** (2 connections) — `app/Filament/Pages/Auth/AdminLogin.php`
- **.getCredentialsFromFormData()** (2 connections) — `app/Filament/Portal/Pages/Auth/StudentLogin.php`
- **TextInput** (2 connections)

## Relationships

- [Filament Panel Providers](Filament_Panel_Providers.md) (3 shared connections)
- [Student Model & Observer](Student_Model_&_Observer.md) (2 shared connections)
- [TGIPay & Webhook Controllers](TGIPay_&_Webhook_Controllers.md) (1 shared connections)
- [Instructor Resource](Instructor_Resource.md) (1 shared connections)

## Source Files

- `app/Filament/Pages/Auth/AdminLogin.php`
- `app/Filament/Portal/Pages/Auth/StudentLogin.php`

## Audit Trail

- EXTRACTED: 17 (94%)
- INFERRED: 1 (6%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*