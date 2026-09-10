<?php

namespace App\Filament\Inventory\Resources\InventoryItemResource\Pages;

use App\Filament\Inventory\Imports\AjahInventoryImporter;
use App\Filament\Inventory\Resources\InventoryItemResource;
use App\Filament\Inventory\Support\ViewToggle;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListInventoryItems extends ListRecords
{
    protected static string $resource = InventoryItemResource::class;

    public function getTitle(): string
    {
        return 'All items';
    }

    public function getSubheading(): ?string
    {
        return 'Every item across the rooms you can see.';
    }

    protected function getHeaderActions(): array
    {
        return array_merge(ViewToggle::make('items'), [
            Actions\ImportAction::make()
                ->label('Import inventory CSV')
                ->visible(fn (): bool => Auth::user()?->can('item.import') ?? false)
                ->importer(AjahInventoryImporter::class),
            Actions\CreateAction::make()->label('Add item'),
        ]);
    }
}
