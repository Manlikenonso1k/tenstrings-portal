<?php

namespace App\Filament\Portal\Pages;

use App\Models\Grade;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class StudentAcademicPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.portal.pages.student-academic-page';

    public function getAcademicStats(): array
    {
        $studentId = Auth::user()?->student?->id;
        $student = Auth::user()?->student;

        if (! $studentId) {
            return [];
        }

        $months = ['FEBRUARY', 'MAY', 'AUGUST', 'NOVEMBER'];
        $stats = [];

        foreach ($months as $month) {
            $query = Grade::query()->where('student_id', $studentId)->where('assessment_month', $month);
            $count = $query->count();

            $stats[$month] = $count === 0
                ? 'No assessment yet'
                : $count . ' assessment(s) | Avg: ' . round((float) $query->avg('percentage'), 2) . '%';
        }

        return [
            'stats' => $stats,
            'student' => $student,
        ];
    }
}
