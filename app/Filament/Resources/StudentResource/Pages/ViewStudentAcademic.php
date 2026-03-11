<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\Grade;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentAcademic extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    protected static string $view = 'filament.resources.student-resource.pages.view-student-academic';

    public function getAcademicStats(): array
    {
        $studentId = $this->record->id;
        $stats = [];

        foreach (['FEBRUARY', 'MAY', 'AUGUST', 'NOVEMBER'] as $month) {
            $query = Grade::query()->where('student_id', $studentId)->where('assessment_month', $month);
            $count = $query->count();

            $stats[$month] = $count === 0
                ? 'No assessment yet'
                : $count . ' assessment(s) | Avg: ' . round((float) $query->avg('percentage'), 2) . '%';
        }

        return $stats;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back_to_hub')
                ->label('Back to Hub')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => ViewStudent::getUrl(['record' => $this->record->getRouteKey()])),
        ];
    }
}
