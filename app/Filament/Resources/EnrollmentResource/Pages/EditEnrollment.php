<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnrollment extends EditRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        EnrollmentResource::validateMaxTwoCourses(
            (int) $data['student_id'],
            $this->data['courses'] ?? [],
            (int) $this->record->id
        );

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
