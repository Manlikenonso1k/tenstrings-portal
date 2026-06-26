<?php

use App\Http\Controllers\Api\V1\AnnouncementController;
use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CalendarController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\GradeController;
use App\Http\Controllers\Api\V1\PaymentApiController;
use App\Http\Controllers\Api\V1\StudentProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenstrings Portal — Mobile API Routes (v1)
|--------------------------------------------------------------------------
| These routes are stateless and use Sanctum token-based auth.
| They are completely isolated from Filament's session-based guards.
| All routes are prefixed with /api/v1 automatically.
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ── MODULE 1: AUTHENTICATION ─────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('/login',    [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout',  [AuthController::class, 'logout']);
            Route::get('/me',       [AuthController::class, 'me']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
        });
    });

    // ── PROTECTED ROUTES (require valid Bearer token) ─────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // ── MODULE 2: STUDENT PROFILE ─────────────────────────────────────
        Route::prefix('student')->group(function () {
            Route::get('/',                 [StudentProfileController::class, 'show']);
            Route::patch('/',               [StudentProfileController::class, 'update']);
            Route::post('/avatar',          [StudentProfileController::class, 'uploadAvatar']);
            Route::get('/documents',        [StudentProfileController::class, 'documents']);
            Route::post('/change-password', [StudentProfileController::class, 'changePassword']);
        });

        // ── MODULE 3: COURSES & LESSONS ───────────────────────────────────
        Route::prefix('courses')->group(function () {
            Route::get('/', [CourseController::class, 'index']);
            Route::get('/{course}', [CourseController::class, 'show']);
            Route::get('/{course}/modules', [CourseController::class, 'modules']);
            Route::get('/{course}/modules/{module}/lessons', [CourseController::class, 'lessons']);
            Route::get('/{course}/modules/{module}/lessons/{lesson}', [CourseController::class, 'lesson']);
            Route::post('/{course}/modules/{module}/lessons/{lesson}/complete', [CourseController::class, 'completeLesson']);
        });

        // ── MODULE 4: RESULTS / GRADES ────────────────────────────────────
        Route::prefix('grades')->group(function () {
            Route::get('/',                [GradeController::class, 'index']);
            Route::get('/summary',         [GradeController::class, 'summary']);
            Route::get('/courses/{course}',[GradeController::class, 'byCourse']);
        });

        // ── MODULE 4b: ATTENDANCE ─────────────────────────────────────────
        Route::prefix('attendance')->group(function () {
            Route::get('/',        [AttendanceController::class, 'index']);
            Route::get('/summary', [AttendanceController::class, 'summary']);
        });

        // ── MODULE 5: PAYMENTS & FEE STATUS ──────────────────────────────
        Route::prefix('payments')->group(function () {
            Route::get('/',                    [PaymentApiController::class, 'index']);
            Route::get('/fee-status',          [PaymentApiController::class, 'feeStatus']);
            Route::get('/{payment}',           [PaymentApiController::class, 'show']);
            Route::get('/{payment}/receipt',   [PaymentApiController::class, 'receipt']);
            Route::post('/initiate/{gateway}', [PaymentApiController::class, 'initiate']);
        });

        // ── MODULE 6: ANNOUNCEMENTS ───────────────────────────────────────
        Route::prefix('announcements')->group(function () {
            Route::get('/',               [AnnouncementController::class, 'index']);
            Route::get('/{announcement}', [AnnouncementController::class, 'show']);
        });

        // ── MODULE 6b: CALENDAR ───────────────────────────────────────────
        Route::prefix('calendar')->group(function () {
            Route::get('/', [CalendarController::class, 'index']);
        });

    }); // end auth:sanctum
}); // end v1
