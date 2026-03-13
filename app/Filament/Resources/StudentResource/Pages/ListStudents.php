<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Imports\StudentImporter;
use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ImportAction::make()
                ->label('Import Students CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->importer(StudentImporter::class)
                ->chunkSize(100)
                ->maxRows(5000),
        ];
    }
}
