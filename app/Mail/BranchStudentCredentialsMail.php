<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BranchStudentCredentialsMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Student $student,
        public string $plainPassword
    ) {
    }

    public function build(): self
    {
        $studentName = trim($this->student->first_name . ' ' . $this->student->last_name);

        return $this
            ->subject('New Student Credentials: ' . $studentName)
            ->view('emails.branch-student-credentials', [
                'student' => $this->student,
                'plainPassword' => $this->plainPassword,
                'studentName' => $studentName,
            ]);
    }
}
