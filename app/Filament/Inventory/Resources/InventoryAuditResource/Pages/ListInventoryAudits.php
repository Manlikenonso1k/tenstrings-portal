<?php

namespace App\Filament\Inventory\Resources\InventoryAuditResource\Pages;

use App\Filament\Inventory\Resources\InventoryAuditResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryAudits extends ListRecords
{
    protected static string $resource = InventoryAuditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
