<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class StudentPdfController extends Controller
{
    public function admissionLetter(Student $student): Response
    {
        $this->authorizeStudentAccess($student);

        $pdf = Pdf::loadView('pdf.admission-letter', [
            'student' => $student,
        ]);

        return $pdf->download('admission-letter-' . $student->student_number . '.pdf');
    }

    public function biodata(Student $student): Response
    {
        $this->authorizeStudentAccess($student);

        $pdf = Pdf::loadView('pdf.biodata', [
            'student' => $student,
        ]);

        return $pdf->download('biodata-' . $student->student_number . '.pdf');
    }

    private function authorizeStudentAccess(Student $student): void
    {
        $user = auth()->user();

        if (! $user) {
            abort(403);
        }

        if (($user->isAdmin() ?? false) || $student->user_id === $user->id) {
            return;
        }

        abort(403);
    }
}
