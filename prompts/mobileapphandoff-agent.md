# Tenstrings Mobile App — Agent Handoff Document

**To the Next AI Agent:**
You are taking over the development of the **Tenstrings Music School Mobile App**. Your environment is entirely focused on the mobile frontend (e.g., React Native / Expo or Swift). 

The backend web portal (Laravel 12 + Filament) is already built, active, and functioning. **Do not modify the backend architecture** unless absolutely necessary.

---

## 1. System Architecture & Constraints

- **Backend Framework:** Laravel 12
- **Mobile API Paradigm:** Stateless REST JSON API.
- **Authentication:** **Laravel Sanctum (Token-based only).** 
- **CRITICAL RULE:** The web backend uses Filament with stateful (cookie-based) session guards for its admin/student portals. **You must strictly use Bearer tokens for the mobile app.** Do not attempt to use Sanctum's SPA cookie authentication, as it will conflict with Filament.

## 2. API Endpoints Reference

All API routes live under the `api/v1` prefix and are defined in `routes/api.php` on the backend. 
**Base URL:** `https://your-production-url.com/api/v1` (Fallback to localhost for dev).

### Authentication (`/auth`)
- `POST /auth/login` (Body: `email`, `password`, `device_name`) → Returns `token`, `user`, `student`.
- `POST /auth/logout` (Requires Bearer token) → Revokes current token.
- `GET /auth/me` (Requires Bearer token) → Returns current profile summary.
- `POST /auth/refresh` (Requires Bearer token) → Rotates token.

### Student Profile (`/student`)
- `GET /student` → Detailed JSON of the student profile.
- `PATCH /student` → Update fields (`phone`, `address`, `guardian_*`).
- `POST /student/avatar` → Multipart form upload for avatar image.
- `POST /student/change-password` → (Body: `current_password`, `password`, `password_confirmation`). Revokes all tokens on success!
- `GET /student/documents` → Returns signed URLs for docs (photo, birth certificate, waec, etc).

### Academic & Learning
- **Courses**: `GET /courses` (List), `GET /courses/{id}` (Detail).
- **Modules**: `GET /courses/{id}/modules` (Lists modules and `is_unlocked` status).
- **Lessons**: `GET /courses/{c_id}/modules/{m_id}/lessons` and `GET /courses/{c_id}/modules/{m_id}/lessons/{l_id}`.
- **Lesson Completion**: `POST /courses/{c_id}/modules/{m_id}/lessons/{l_id}/complete`.
- **Grades**: `GET /grades` (Paginated) and `GET /grades/summary` (Stats).
- **Attendance**: `GET /attendance` (Paginated) and `GET /attendance/summary`.

### Payments (`/payments`)
- `GET /payments` → History.
- `GET /payments/fee-status` → Outstanding balances per course.
- `GET /payments/{id}` → Detail.
- `GET /payments/{id}/receipt` → Returns a temporary signed URL to download the PDF receipt.
- *Note:* Initiation of actual payments via mobile is a stub (`501 Not Implemented`) and will need a WebView or deep linking strategy in the future.

### School Updates
- `GET /announcements` → Paginated global and student announcements.
- `GET /calendar` → Upcoming events (classes, payments, school events).

## 3. Frontend Development Directives

1. **Token Storage:** You must store the authentication token securely. 
   - *If React Native:* Use `expo-secure-store` (do NOT use `AsyncStorage`).
   - *If Swift:* Use `Keychain`.
2. **HTTP Client Interceptors:** Configure your HTTP client (e.g., Axios) to automatically attach `Authorization: Bearer <token>` to every request. Add global error handling for `401 Unauthorized` responses to automatically clear the local token and redirect the user to the Login screen.
3. **Data Fetching:** Standardize data fetching (e.g., React Query or SWR) to handle the `data` wrapper that Laravel API Resources return (e.g., `response.data.data`).

## 4. Getting Started

1. Initialize your mobile project framework.
2. Build the Auth flow (Login Screen -> Secure Store -> Main App Stack).
3. Build the Dashboard (fetching from `/auth/me` and `/announcements`).
4. Proceed to build out the remaining screens mapping to the core API endpoints above.
