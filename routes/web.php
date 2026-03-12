<?php

use App\Http\Controllers\Auth\StudentRegistrationController;
use App\Http\Controllers\Student\StudentPdfController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! Auth::check()) {
        return redirect('/portal/login');
    }

    $user = Auth::user();

    return redirect(match ($user?->role) {
        'student' => '/portal',
        'super_admin', 'admin', 'instructor' => '/admin',
        default => '/portal/login',
    });
});

Route::get('/register/student', [StudentRegistrationController::class, 'create'])
    ->name('student.register');

Route::post('/register/student', [StudentRegistrationController::class, 'store'])
    ->name('student.register.store');

Route::middleware('auth')->group(function () {
    Route::get('/students/{student}/print/admission-letter', [StudentPdfController::class, 'admissionLetter'])
        ->name('students.print.admission_letter');

    Route::get('/students/{student}/print/biodata', [StudentPdfController::class, 'biodata'])
        ->name('students.print.biodata');
});
