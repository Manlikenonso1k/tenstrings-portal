<?php

namespace App\Support;

use App\Mail\BranchStudentCredentialsMail;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;

class StudentMatricMailer
{
    private const MASTER_EMAIL = 'victorynonso9@gmail.com';

    public static function send(Student $student): void
    {
        Mail::raw(
            "Hello {$student->first_name},\n\n" .
            "Your Tenstrings Portal registration is successful.\n" .
            "Your matric number is: {$student->student_number}\n\n" .
            "Login here: " . url('/portal/login') . "\n\n" .
            "Regards,\nTenstrings Portal",
            function ($message) use ($student) {
                $message
                    ->to($student->email)
                    ->subject('Your Tenstrings Matric Number');
            }
        );
    }

    public static function sendWithCredentials(Student $student, string $plainPassword): void
    {
        Mail::raw(
            "Hello {$student->first_name},\n\n" .
            "Your Tenstrings Portal account has been created.\n" .
            "Matric number: {$student->student_number}\n" .
            "Temporary password: {$plainPassword}\n\n" .
            "Login here: " . url('/portal/login') . "\n" .
            "Please change your password after your first login.\n\n" .
            "Regards,\nTenstrings Portal",
            function ($message) use ($student) {
                $message
                    ->to($student->email)
                    ->subject('Your Tenstrings Portal Login Credentials');
            }
        );
    }

    public static function sendBranchCredentials(Student $student, string $plainPassword): void
    {
        $branchEmail = self::branchRecipientFor((string) $student->branch);

        Mail::to($branchEmail)
            ->cc(self::MASTER_EMAIL)
            ->send(new BranchStudentCredentialsMail($student, $plainPassword));
    }

    private static function branchRecipientFor(string $branch): string
    {
        $value = strtoupper(trim($branch));

        if (str_contains($value, 'IKEJA')) {
            return 'tenstringsikeja@gmail.com';
        }

        if (str_contains($value, 'AGEGE')) {
            return 'tenstringsagege@gmail.com';
        }

        return 'tenstringsajah@gmail.com';
    }
}
