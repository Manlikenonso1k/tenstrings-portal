<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Support\EnrollmentLimitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_cannot_exceed_two_ongoing_courses(): void
    {
        $student = Student::query()->create([
            'student_number' => 'STU-9999',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => 'limit-test@example.com',
            'phone' => '+23400000000',
            'registration_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $courses = collect(range(1, 3))->map(function ($index) {
            return Course::query()->create([
                'name' => 'Course ' . $index,
                'duration_months' => 3,
                'duration_label' => '3 months',
                'course_fee' => 1000,
                'description' => 'Test Course',
                'max_students_per_class' => 30,
                'is_active' => true,
            ]);
        });

        $enrollment = Enrollment::query()->create([
            'enrollment_number' => 'ENR-TEST-1',
            'student_id' => $student->id,
            'enrollment_date' => now()->toDateString(),
            'start_date' => now()->toDateString(),
            'expected_end_date' => now()->addMonths(3)->toDateString(),
            'status' => 'ongoing',
        ]);

        $enrollment->courses()->attach([$courses[0]->id, $courses[1]->id]);

        $canEnrollThird = EnrollmentLimitService::canEnrollInCourses(
            $student->id,
            [$courses[2]->id]
        );

        $this->assertFalse($canEnrollThird);
    }
}
