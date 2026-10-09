<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentCourseFee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCorrectionCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_corrects_a_payment_and_reconciles_student_balances(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = Course::query()->create([
            'code' => 'CRS-CORR-001',
            'name' => 'Correction Test Course',
            'duration_months' => 3,
            'duration_label' => '3 months',
            'course_fee' => 500000,
        ]);
        $student = Student::query()->create([
            'student_number' => 'STU-CORRECTION-001',
            'first_name' => 'Ada',
            'last_name' => 'Okafor',
            'email' => 'ada.correction@example.test',
            'phone' => '+2348000000000',
            'registration_date' => now()->toDateString(),
            'status' => 'active',
        ]);
        $fee = StudentCourseFee::query()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'total_course_fee' => 500000,
            'amount_paid' => 0,
            'outstanding_balance' => 500000,
            'status' => 'pending',
        ]);
        $payment = Payment::query()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'amount_paid' => 1800000,
            'amount' => 1800000,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'transfer',
            'payment_status' => 'paid',
            'status' => 'success',
            'receipt_number' => 'REC-CORRECTION-001',
        ]);

        $this->artisan('payments:correct-amount', [
            'payment' => $payment->id,
            'amount' => 180000,
            '--actor-id' => $admin->id,
            '--confirm' => true,
        ])->assertSuccessful();

        $this->assertSame(180000.0, (float) $payment->fresh()->amount_paid);
        $this->assertSame(180000.0, (float) $payment->fresh()->amount);
        $this->assertSame(180000.0, (float) $fee->fresh()->amount_paid);
        $this->assertSame(320000.0, (float) $fee->fresh()->outstanding_balance);
        $this->assertDatabaseHas('activity_log', [
            'description' => 'payment_amount_corrected',
            'causer_id' => $admin->id,
        ]);
    }
}