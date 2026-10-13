<?php

namespace App\Filament\Portal\Pages\Auth;

use App\Models\Student;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login;

class StudentLogin extends Login
{
    protected static string $view = 'filament.portal.pages.auth.student-login';

    protected function getEmailFormComponent(): TextInput
    {
        return TextInput::make('email')
            ->label('Matric Number')
            ->placeholder('e.g. 17010301045')
            ->required()
            ->autocomplete('username')
            ->autofocus();
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        $input = trim((string) ($data['email'] ?? ''));

        $student = Student::query()->where('student_number', $input)->first();

        // Resolve through Student::loginUser() so this agrees with the admin
        // password-reset action. Falling back to the linked user's email also
        // lets a student sign in when students.email is blank or points at no
        // account, which previously just failed.
        $email = $student?->loginUser()?->email ?? $student?->email;

        return [
            'email' => filled($email) ? $email : $input,
            'password' => $data['password'],
        ];
    }
}
