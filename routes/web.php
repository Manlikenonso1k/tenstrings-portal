<?php

use App\Http\Controllers\Auth\StudentRegistrationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Student\StudentPdfController;
use App\Http\Controllers\WebhookController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
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

    Route::post('/payments/{gateway}/initialize', [PaymentController::class, 'initialize'])
        ->name('payments.initialize');

    Route::post('/portal/payments/outstanding', [PaymentController::class, 'payOutstanding'])
        ->name('portal.payments.pay_outstanding');

    Route::get('/portal/payments/callback', [PaymentController::class, 'callback'])
        ->name('portal.payments.callback');

    Route::get('/payments/{gateway}/verify/{reference}', [PaymentController::class, 'verify'])
        ->name('payments.verify');

    Route::get('/documents/invoices/{invoice}', [PaymentController::class, 'downloadInvoice'])
        ->name('documents.invoices.download')
        ->middleware('signed');

    Route::get('/documents/receipts/{payment}', [PaymentController::class, 'downloadReceipt'])
        ->name('documents.receipts.download')
        ->middleware('signed');
});

Route::post('/webhooks/{gateway}', [WebhookController::class, 'handle'])
    ->name('payments.webhook')
    ->withoutMiddleware([VerifyCsrfToken::class]);
