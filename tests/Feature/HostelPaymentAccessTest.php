<?php

namespace Tests\Feature;

use App\Models\HostelPayment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HostelPaymentAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_accounts_clerk_can_create_hostel_payment_records_for_a_student(): void
    {
        $clerk = User::factory()->create(['role' => 'accounts_clerk']);
        $student = Student::query()->create([
            'student_number' => 'STU-HOSTEL-001',
            'first_name' => 'Ada',
            'last_name' => 'Okafor',
            'email' => 'ada.hostel@example.test',
            'phone' => '+2348000000000',
            'registration_date' => now()->toDateString(),
            'hostel_fee' => 150000,
            'status' => 'active',
        ]);

        $payment = $student->hostelPayments()->create([
            'recorded_by' => $clerk->id,
            'amount' => 50000,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'transfer',
            'status' => 'paid',
            'receipt_number' => 'HREC-TEST-001',
        ]);

        $this->assertTrue($clerk->can('create', HostelPayment::class));
        $this->assertSame('HST-00001', $payment->payment_number);
        $this->assertSame(50000.0, (float) $student->hostelPayments()->where('status', 'paid')->sum('amount'));
    }
}