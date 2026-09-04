<?php

namespace App\Filament\Inventory\Resources\InventoryCategoryResource\Pages;

use App\Filament\Inventory\Resources\InventoryCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInventoryCategory extends CreateRecord
{
    protected static string $resource = InventoryCategoryResource::class;
}
