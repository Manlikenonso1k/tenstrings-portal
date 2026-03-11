<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Support\CourseCatalog;
use App\Support\StudentMatricMailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class StudentRegistrationController extends Controller
{
    public function create(): View
    {
        return view('auth.student-register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', 'unique:students,email'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
            'branch' => ['required', 'in:AJAH BRANCH,AGEGE BRANCH,IKEJA BRANCH,FESTAC BRANCH'],
            'selected_course_name' => ['required', Rule::in(array_keys(CourseCatalog::courseOptions()))],
            'duration' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'date_of_birth' => ['nullable', 'date'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'guardian_email' => ['nullable', 'email', 'max:255'],
            'guardian_relationship' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if (! CourseCatalog::isValidDurationFor($validated['selected_course_name'], $validated['duration'])) {
            throw ValidationException::withMessages([
                'duration' => 'Selected duration is invalid for the selected course.',
            ]);
        }

        $student = DB::transaction(function () use ($validated) {
            $user = User::query()->create([
                'name' => trim($validated['first_name'] . ' ' . $validated['last_name']),
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'role' => 'student',
                'password' => Hash::make($validated['password']),
            ]);

            return Student::query()->create([
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'selected_course_name' => $validated['selected_course_name'],
                'selected_course_code' => CourseCatalog::codeFor($validated['selected_course_name']),
                'duration' => $validated['duration'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'] ?? null,
                'branch' => $validated['branch'],
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'start_date' => $validated['start_date'],
                'registration_date' => now()->toDateString(),
                'status' => 'active',
                'guardian_name' => $validated['guardian_name'] ?? null,
                'guardian_phone' => $validated['guardian_phone'] ?? null,
                'guardian_email' => $validated['guardian_email'] ?? null,
                'guardian_relationship' => $validated['guardian_relationship'] ?? null,
            ]);
        });

        try {
            StudentMatricMailer::send($student);
        } catch (Throwable $exception) {
            Log::warning('Student matric email could not be sent.', [
                'student_id' => $student->id,
                'email' => $student->email,
                'error' => $exception->getMessage(),
            ]);
        }

        return redirect('/portal/login')->with('status', 'Registration successful. Your matric number is ' . $student->student_number . '. Please login.');
    }
}
