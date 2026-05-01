<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function afterCreate(): void
    {
        $student = $this->record;

        // Skip CSV imports - they carry their own financial snapshot
        if (($student->created_via ?? 'dashboard') === 'csv') {
            return;
        }

        // Try to find the course by selected name
        $course = \App\Models\Course::query()
            ->where('name', (string) $student->selected_course_name)
            ->first();

        if (! $course) {
            return;
        }

        // Create a payment advice snapshot so the portal shows a balance
        \App\Models\PaymentAdvice::query()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'amount' => (float) ($course->course_fee ?? 0),
            'status' => 'pending',
            'generated_at' => now(),
        ]);
    }
}
