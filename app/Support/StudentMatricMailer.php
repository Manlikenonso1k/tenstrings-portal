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
}
