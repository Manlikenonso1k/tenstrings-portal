<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class StudentActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_updates_record_the_student_and_staff_member_in_the_activity_log(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = Student::query()->create([
            'student_number' => 'STU-AUDIT-001',
            'first_name' => 'Ada',
            'last_name' => 'Okafor',
            'email' => 'ada.audit@example.test',
            'phone' => '+2348000000000',
            'registration_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $this->actingAs($admin);
        $student->update(['phone' => '+2348000000001']);

        $activity = Activity::query()
            ->where('subject_type', Student::class)
            ->where('subject_id', $student->id)
            ->where('event', 'updated')
            ->latest('id')
            ->firstOrFail();

        $this->assertSame($admin->id, $activity->causer_id);
        $this->assertSame(User::class, $activity->causer_type);
        $this->assertSame('Ada Okafor', $activity->getExtraProperty('student_name'));
        $this->assertSame('STU-AUDIT-001', $activity->getExtraProperty('student_number'));
    }
}