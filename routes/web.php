<?php

use App\Http\Controllers\Auth\StudentRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register/student', [StudentRegistrationController::class, 'create'])
    ->name('student.register');

Route::post('/register/student', [StudentRegistrationController::class, 'store'])
    ->name('student.register.store');
