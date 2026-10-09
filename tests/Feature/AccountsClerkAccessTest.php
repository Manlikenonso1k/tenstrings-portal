<?php

namespace Tests\Feature;

use App\Filament\Resources\StudentResource;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountsClerkAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_accounts_clerk_can_record_payments_and_edit_student_identity_and_core_information(): void
    {
        $clerk = User::factory()->create(['role' => 'accounts_clerk']);
        $student = Student::query()->create([
            'student_number' => 'STU-ACCOUNTS-001',
            'first_name' => 'Ada',
            'last_name' => 'Okafor',
            'email' => 'ada.okafor@example.test',
            'phone' => '+2348000000000',
            'registration_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $this->assertTrue($clerk->can('create', Payment::class));
        $this->assertTrue($clerk->can('update', $student));

        $this->actingAs($clerk);

        $this->assertTrue(StudentResource::canEdit($student));

    }
}