<?php

namespace App\Filament\Resources\LoginSessionResource\Pages;

use App\Filament\Resources\LoginSessionResource;
use Filament\Resources\Pages\ListRecords;

class ListLoginSessions extends ListRecords
{
    protected static string $resource = LoginSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
