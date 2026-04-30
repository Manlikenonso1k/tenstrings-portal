<?php

use App\Http\Controllers\Auth\StudentRegistrationController;
use App\Http\Controllers\Payments\OnlinePaymentController;
use App\Http\Controllers\Payments\TgiWebhookController;
use App\Http\Controllers\Student\StudentPdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register/student', [StudentRegistrationController::class, 'create'])
    ->name('student.register');

Route::post('/register/student', [StudentRegistrationController::class, 'store'])
    ->name('student.register.store');

Route::get('/portal/payments/callback', [OnlinePaymentController::class, 'callback'])
    ->name('portal.payments.callback');

Route::post('/webhooks/tgi', [TgiWebhookController::class, 'handle'])
    ->name('webhooks.tgi')
    ->withoutMiddleware(['web']);

Route::middleware('auth')->group(function () {
    Route::post('/portal/payments/fees/{fee}/initialize', [OnlinePaymentController::class, 'initialize'])
        ->name('portal.payments.initialize');

    Route::get('/students/{student}/print/admission-letter', [StudentPdfController::class, 'admissionLetter'])
        ->name('students.print.admission_letter');

    Route::get('/students/{student}/print/biodata', [StudentPdfController::class, 'biodata'])
        ->name('students.print.biodata');
});
