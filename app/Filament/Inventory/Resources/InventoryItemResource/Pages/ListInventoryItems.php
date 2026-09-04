<?php

namespace App\Filament\Inventory\Resources\InventoryItemResource\Pages;

use App\Filament\Inventory\Imports\AjahInventoryImporter;
use App\Filament\Inventory\Resources\InventoryItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryItems extends ListRecords
{
    protected static string $resource = InventoryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->label('Import inventory CSV')
                ->visible(fn (): bool => auth()->user()?->can('item.import') ?? false)
                ->importer(AjahInventoryImporter::class),
            Actions\CreateAction::make(),
        ];
    }
}