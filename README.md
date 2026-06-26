# Tenstrings Portal

A comprehensive music school management system built with **Laravel 12** and **Filament PHP v3**.

## Features

- **Multi-Panel Architecture**: Separate Filament panels for Admins, Instructors, and Students.
- **Student Management**: Full lifecycle management from admission to graduation.
- **Academic Core**: Course modules, unlocked lesson progressions, grading, and attendance tracking.
- **Financials**: Integrated fee workflows, invoice generation, and live payments via Paystack and TGIPay.
- **Stateless Mobile API**: A robust `v1` RESTful API designed for a companion mobile app.

---

## 📱 Mobile API (v1)

The portal provides a fully stateless, token-based API (`routes/api.php`) protected by **Laravel Sanctum**. This API is strictly separated from the web portal's session-based Filament guards.

### API Highlights
- **Base URL**: `https://your-domain.com/api/v1`
- **Authentication**: Bearer Tokens (Sanctum)
- **Response Format**: Standardized JSON wrapped in API Resources.

### Core Endpoints Overview

| Module | Base Path | Description |
|---|---|---|
| **Auth** | `/auth` | Login, Logout, Token Refresh, and Profile (`/me`). |
| **Profile** | `/student` | View and edit limited profile info, upload avatars, get document links. |
| **Courses** | `/courses` | Browse courses, access modules, read lessons (respects unlock logic), and mark completion. |
| **Grades** | `/grades` | View all assessment scores and calculate GPA summaries. |
| **Attendance** | `/attendance` | Track presence, absence, and late marks. |
| **Payments** | `/payments` | View history, check outstanding balances, and download signed PDF receipts. |
| **Events** | `/calendar`, `/announcements` | Global school announcements and personalized calendar dates. |

## Local Development

1. Clone the repository
2. Run `composer install` and `npm install`
3. Copy `.env.example` to `.env` and set up database/payment credentials.
4. Run `php artisan key:generate`
5. Run `php artisan migrate` (Sanctum migrations are included)
6. Start the server: `php artisan serve` or `npm run dev`

## Production Deployment Notes

When deploying updates to production, especially changes involving the API and auth guards, run:

```bash
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan optimize:clear
```
