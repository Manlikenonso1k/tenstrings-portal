<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        EnrollmentResource::validateMaxTwoCourses(
            (int) $data['student_id'],
            $this->data['courses'] ?? []
        );

        return $data;
    }
}
