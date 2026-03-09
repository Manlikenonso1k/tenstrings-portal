<?php

namespace App\Filament\Resources\CourseShortcodeMappingResource\Pages;

use App\Filament\Resources\CourseShortcodeMappingResource;
use Filament\Resources\Pages\EditRecord;

class EditCourseShortcodeMapping extends EditRecord
{
    protected static string $resource = CourseShortcodeMappingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
