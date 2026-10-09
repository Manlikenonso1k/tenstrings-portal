<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use App\Services\Payments\PaymentEvidenceApprovalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class PaymentEvidenceApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_developer_approved_otp_replaces_only_the_receipt_evidence(): void
    {
        $clerk = User::factory()->create(['role' => 'accounts_clerk']);
        $student = Student::query()->create([
            'student_number' => 'STU-EVIDENCE-001',
            'first_name' => 'Ada',
            'last_name' => 'Okafor',
            'email' => 'ada.evidence@example.test',
            'phone' => '+2348000000000',
            'registration_date' => now()->toDateString(),
            'status' => 'active',
        ]);
        $payment = Payment::query()->create([
            'student_id' => $student->id,
            'amount_paid' => 50000,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'transfer',
            'payment_status' => 'paid',
            'status' => 'success',
            'receipt_number' => 'REC-EVIDENCE-001',
            'receipt_evidence_path' => 'payments/evidence/original.pdf',
        ]);

        Cache::put('payment-evidence-approval:' . $payment->id . ':' . $clerk->id, [
            'code_hash' => Hash::make('123456'),
            'replacement_path' => 'payments/evidence/replacement.pdf',
            'attempts' => 0,
        ], now()->addMinutes(10));

        app(PaymentEvidenceApprovalService::class)->replace($payment, $clerk, '123456');

        $payment->refresh();

        $this->assertSame('payments/evidence/replacement.pdf', $payment->receipt_evidence_path);
        $this->assertSame(50000.0, (float) $payment->amount_paid);
        $this->assertDatabaseHas('activity_log', [
            'description' => 'payment_evidence_replaced_with_developer_approval',
            'causer_id' => $clerk->id,
        ]);
        $this->assertSame('STU-EVIDENCE-001', Activity::latest('id')->firstOrFail()->getExtraProperty('student_number'));
    }
}