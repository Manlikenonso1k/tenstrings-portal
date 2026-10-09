<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentCourseFee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentDeletionCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_only_the_duplicate_payment_and_reconciles_the_student_balance(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = Course::query()->create([
            'code' => 'CRS-DELETE-001',
            'name' => 'Deletion Test Course',
            'duration_months' => 12,
            'duration_label' => '1 year',
            'course_fee' => 3600000,
        ]);
        $student = Student::query()->create([
            'student_number' => 'STU-DELETION-001',
            'first_name' => 'Ada',
            'last_name' => 'Okafor',
            'email' => 'ada.deletion@example.test',
            'phone' => '+2348000000000',
            'registration_date' => now()->toDateString(),
            'status' => 'active',
        ]);
        $fee = StudentCourseFee::query()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'total_course_fee' => 3600000,
            'amount_paid' => 0,
            'outstanding_balance' => 3600000,
            'status' => 'pending',
        ]);
        Payment::query()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'amount_paid' => 1800000,
            'amount' => 1800000,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'transfer',
            'payment_status' => 'paid',
            'status' => 'success',
            'receipt_number' => 'REC-VALID-001',
        ]);
        $duplicate = Payment::query()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'amount_paid' => 1800000,
            'amount' => 1800000,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'transfer',
            'payment_status' => 'paid',
            'status' => 'success',
            'receipt_number' => 'REC-DUPLICATE-001',
        ]);

        $this->artisan('payments:delete-and-reconcile', [
            'payment' => $duplicate->id,
            '--actor-id' => $admin->id,
            '--confirm' => true,
        ])->assertSuccessful();

        $this->assertDatabaseMissing('payments', ['id' => $duplicate->id]);
        $this->assertSame(1, Payment::query()->where('student_id', $student->id)->count());
        $this->assertSame(1800000.0, (float) $fee->fresh()->amount_paid);
        $this->assertSame(1800000.0, (float) $fee->fresh()->outstanding_balance);
        $this->assertSame(1800000.0, (float) $student->fresh()->fees_paid);
        $this->assertDatabaseHas('activity_log', [
            'description' => 'duplicate_payment_deletion_approved',
            'causer_id' => $admin->id,
        ]);
    }
}