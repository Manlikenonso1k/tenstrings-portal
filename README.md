# Tenstrings Music School Management System

Laravel 12 + Filament 3 based management system for Tenstrings with Admin, Instructor, and Student role support.

## Implemented Modules

- Role-based authentication (`admin`, `instructor`, `student`) via `users.role`
- Student management (profile, guardian data, status, photo upload)
- Instructor management
- Course catalog management
- Enrollment management with **strict max 2 ongoing courses per student**
- Attendance management
- Grade management (auto percentage + letter grade)
- Assignment management
- Payment management with outstanding-balance validation
- Dashboard widgets (student count, active courses, revenue, pending fees, recent enrollments)
- Seeders for required course catalog + sample users

## Course Catalog Seeded

The seeder includes all requested course families, including 3/6 month variants where applicable.

## Validation Rules Implemented

- Enrollment limit: max 2 ongoing courses per student
- Email fields use email validation and unique constraints
- File uploads: max 10MB (images for profile uploads)
- Payment amount cannot exceed outstanding balance
- Date validation: expected end date must be after start date

## Quick Setup

1. Install dependencies:

```bash
composer install
npm install
```

2. Configure environment:

```bash
cp .env.example .env
php artisan key:generate
```

3. Set database credentials in `.env`, then run:

```bash
php artisan migrate --seed
```

For restricted shared hosting environments where `storage:link` fails, this project uses the `public_uploads` disk (`public/uploads`) for profile uploads, so no symlink is required.

4. Run app:

```bash
php artisan serve
npm run dev
```

5. Access Filament admin panel:

- URL: `/admin`
- Admin login: `admin@tenstrings.org`
- Password: `password`

## Sample Users

- Admin: `admin@tenstrings.org` / `password`
- Instructor: `instructor@tenstrings.org` / `password`
- Student: `student@tenstrings.org` / `password`

## Bash Command Note

Use full artisan commands only:

- ✅ `php artisan list | grep filament`
- ✅ `php artisan make:filament-resource Student --generate`
- ❌ `&& php artisan ...` (invalid at start of line)
- ❌ `route:list` (must be `php artisan route:list`)

## Next Recommended Additions

- PDF/Excel exports for reports and transcripts
- Email/SMS reminder jobs
- Certificate PDF generation workflow
- Student and instructor dedicated panel providers (optional)
