<?php

namespace Tests\Feature;

use App\Filament\Portal\Pages\Auth\StudentLogin;
use App\Filament\Resources\StudentResource\Pages\ViewStudent;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class StudentPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::factory()->create(['role' => 'super_admin']);
    }

    /**
     * Students sign in with their matric number, which StudentLogin resolves to
     * `students.email`. So a reset only works if it lands on the user row that
     * owns that email.
     */
    protected function studentWith(string $studentEmail, string $userEmail, string $oldPassword = 'oldpassword'): Student
    {
        $user = User::factory()->create([
            'email' => $userEmail,
            'role' => 'student',
            'password' => $oldPassword,
        ]);

        return Student::query()->create([
            'user_id' => $user->getKey(),
            'student_number' => '17010301045',
            'first_name' => 'Ada',
            'last_name' => 'Obi',
            'email' => $studentEmail,
            'phone' => '+2348000000010',
            'registration_date' => now()->toDateString(),
            'status' => 'active',
        ]);
    }

    /**
     * Drives the real StudentLogin credential resolution rather than a copy of
     * it — a copy drifts the moment the page changes, and then the test passes
     * while login is broken.
     */
    protected function loginAsStudent(string $matric, string $password): bool
    {
        $page = new StudentLogin();

        $resolve = new \ReflectionMethod($page, 'getCredentialsFromFormData');
        $resolve->setAccessible(true);

        $credentials = $resolve->invoke($page, [
            'email' => $matric,
            'password' => $password,
        ]);

        return Auth::attempt($credentials);
    }

    protected function resetPasswordVia(Student $student, string $new): void
    {
        Livewire::actingAs($this->admin())
            ->test(ViewStudent::class, ['record' => $student->getKey()])
            ->callAction('reset_student_password', data: [
                'new_password' => $new,
                'new_password_confirmation' => $new,
            ])
            ->assertHasNoActionErrors();
    }

    public function test_reset_works_when_the_student_and_user_emails_agree(): void
    {
        $student = $this->studentWith('ada.obi@tenstrings.org', 'ada.obi@tenstrings.org');

        $this->resetPasswordVia($student, 'brandnewpass');

        $this->assertTrue(
            $this->loginAsStudent('17010301045', 'brandnewpass'),
            'Student should be able to sign in with the new password.',
        );
    }

    /**
     * The real-world case. The CSV importer mints placeholder addresses
     * (ensureImportEmail), so students.email and users.email drift apart all
     * the time. Login follows students.email; the reset must too.
     */
    public function test_reset_works_when_the_student_email_differs_from_the_user_email(): void
    {
        $student = $this->studentWith('ada.obi@tenstrings.org', 'ada_obi_17010301045@tenstrings.org');

        $this->resetPasswordVia($student, 'brandnewpass');

        $this->assertTrue(
            $this->loginAsStudent('17010301045', 'brandnewpass'),
            'Reset landed on a different user row than the one login resolves to.',
        );
    }

    /**
     * A second user row already owning students.email is the shape that makes
     * the reset silently hit the wrong record.
     */
    public function test_reset_targets_the_user_row_that_owns_the_student_email(): void
    {
        $student = $this->studentWith('ada.obi@tenstrings.org', 'ada_obi_placeholder@tenstrings.org');

        $loginUser = User::factory()->create([
            'email' => 'ada.obi@tenstrings.org',
            'role' => 'student',
            'password' => 'oldpassword',
        ]);

        $this->resetPasswordVia($student, 'brandnewpass');

        $this->assertTrue(
            Hash::check('brandnewpass', $loginUser->fresh()->password),
            'The user row that login authenticates against was not updated.',
        );

        $this->assertTrue($this->loginAsStudent('17010301045', 'brandnewpass'));
    }

    public function test_reset_stores_a_usable_hash_and_not_a_double_hash(): void
    {
        $student = $this->studentWith('ada.obi@tenstrings.org', 'ada.obi@tenstrings.org');

        $this->resetPasswordVia($student, 'brandnewpass');

        $password = (string) $student->fresh()->user->password;

        $this->assertTrue(Hash::check('brandnewpass', $password), 'Stored hash does not verify.');
        $this->assertFalse(Hash::check(Hash::make('brandnewpass'), $password));
    }
}
