<?php

namespace App\Observers;

use App\Models\Course;
use App\Models\Student;
use App\Models\StudentCourseFee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentObserver
{
    /**
     * Handle the Student "created" event.
     * 
     * Automatically creates a User account and StudentCourseFee when a student
     * is created via the dashboard (not CSV import).
     */
    public function created(Student $student): void
    {
        // Only auto-create for dashboard entries
        if (($student->created_via ?? 'dashboard') !== 'dashboard') {
            return;
        }

        // Auto-create User account if not linked
        if (! $student->user_id && $student->email) {
            $this->createUserAccount($student);
        }

        // Auto-generate StudentCourseFee record
        if ($student->selected_course_name) {
            $this->createStudentCourseFee($student);
        }
    }

    /**
     * Handle the Student "updated" event.
     * 
     * Auto-generate StudentCourseFee if course changes (dashboard only).
     */
    public function updated(Student $student): void
    {
        // Only auto-create for dashboard entries
        if (($student->created_via ?? 'dashboard') !== 'dashboard') {
            return;
        }

        // Check if course selection changed
        if ($student->isDirty('selected_course_name') && $student->selected_course_name) {
            $this->createStudentCourseFee($student);
        }
    }

    /**
     * Create a User account for the student.
     */
    private function createUserAccount(Student $student): void
    {
        try {
            $email = strtolower(trim((string) $student->email));

            // Check if user already exists
            $existingUser = User::query()
                ->whereRaw('LOWER(email) = ?', [$email])
                ->first();

            if ($existingUser) {
                $student->update(['user_id' => $existingUser->id]);

                Log::info('Linked existing user account to student', [
                    'student_id' => $student->id,
                    'user_id' => $existingUser->id,
                    'email' => $email,
                ]);

                return;
            }

            // Create new user account
            $user = User::query()->create([
                'name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')),
                'email' => $email,
                'phone' => (string) ($student->phone ?? 'N/A'),
                'role' => 'student',
                'password' => Hash::make('password123'), // Default password
            ]);

            $student->update(['user_id' => $user->id]);

            Log::info('Auto-created user account for student', [
                'student_id' => $student->id,
                'user_id' => $user->id,
                'email' => $email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to auto-create user account for student', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Create StudentCourseFee record for the student.
     */
    private function createStudentCourseFee(Student $student): void
    {
        try {
            $course = Course::query()
                ->where('name', (string) $student->selected_course_name)
                ->first();

            if (! $course) {
                Log::warning('Course not found for student', [
                    'student_id' => $student->id,
                    'course_name' => $student->selected_course_name,
                ]);

                return;
            }

            // Check if fee record already exists
            $existingFee = StudentCourseFee::query()
                ->where('student_id', $student->id)
                ->where('course_id', $course->id)
                ->first();

            if ($existingFee) {
                // Don't overwrite existing fee record
                return;
            }

            // Determine required amount based on course duration
            $courseFee = (float) ($course->course_fee ?? 0);
            $durationMonths = (int) ($course->duration_months ?? 0);

            // Apply duration-based rules
            if ($durationMonths < 12) {
                // Short courses: 100% required
                $requiredAmount = $courseFee;
            } else {
                // Long courses: 70% required (minimum deposit)
                $requiredAmount = round($courseFee * 0.7, 2);
            }

            // Create StudentCourseFee record
            StudentCourseFee::query()->create([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'total_course_fee' => $courseFee,
                'amount_paid' => 0,
                'outstanding_balance' => $requiredAmount,
                'status' => 'pending',
            ]);

            Log::info('Auto-created StudentCourseFee for student', [
                'student_id' => $student->id,
                'course_id' => $course->id,
                'total_fee' => $courseFee,
                'required_amount' => $requiredAmount,
                'duration_months' => $durationMonths,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to auto-create StudentCourseFee for student', [
                'student_id' => $student->id,
                'course_name' => $student->selected_course_name,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
