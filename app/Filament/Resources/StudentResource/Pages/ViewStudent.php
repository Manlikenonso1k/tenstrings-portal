<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStudent extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    protected static string $view = 'filament.resources.student-resource.pages.view-student-hub';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
