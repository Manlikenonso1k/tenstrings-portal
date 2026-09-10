<?php

namespace App\Filament\Inventory\Resources\InventoryRoomResource\Pages;

use App\Filament\Inventory\Resources\InventoryRoomResource;
use App\Filament\Inventory\Support\ViewToggle;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryRooms extends ListRecords
{
    protected static string $resource = InventoryRoomResource::class;

    public function getTitle(): string
    {
        return 'Rooms';
    }

    public function getSubheading(): ?string
    {
        return 'Open a room to see what is in it.';
    }

    protected function getHeaderActions(): array
    {
        return array_merge(ViewToggle::make('rooms'), [
            Actions\CreateAction::make()->label('Add room'),
        ]);
    }
}
