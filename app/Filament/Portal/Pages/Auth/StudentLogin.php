<?php

namespace App\Filament\Portal\Pages\Auth;

use App\Models\Student;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login;

class StudentLogin extends Login
{
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

        return [
            'email' => $student?->email ?? $input,
            'password' => $data['password'],
        ];
    }
}
