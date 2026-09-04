<?php

namespace App\Filament\Inventory\Resources\InventoryRoomResource\Pages;

use App\Filament\Inventory\Resources\InventoryRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryRooms extends ListRecords
{
    protected static string $resource = InventoryRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
