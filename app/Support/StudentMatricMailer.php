<?php

namespace App\Support;

use App\Models\Student;
use Illuminate\Support\Facades\Mail;

class StudentMatricMailer
{
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
}
