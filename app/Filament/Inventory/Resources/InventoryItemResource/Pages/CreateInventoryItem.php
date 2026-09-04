<?php

namespace App\Filament\Inventory\Resources\InventoryItemResource\Pages;

use App\Filament\Inventory\Resources\InventoryItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInventoryItem extends CreateRecord
{
    protected static string $resource = InventoryItemResource::class;
}
