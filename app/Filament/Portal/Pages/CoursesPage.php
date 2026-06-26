<?php

namespace App\Filament\Portal\Pages;

use App\Models\Course;
use Filament\Pages\Page;

class CoursesPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'COURSES';

    protected static string $view = 'filament.portal.pages.courses-page';

    protected function getViewData(): array
    {
        $studentId = auth()->user()?->student?->id;

        return [
            'courses' => $studentId
                ? Course::query()
                    ->whereHas('enrollments', function ($query) use ($studentId): void {
                        $query->where('student_id', $studentId);
                    })
                    ->withCount('modules')
                    ->orderBy('name')
                    ->get()
                : collect(),
        ];
    }
}
