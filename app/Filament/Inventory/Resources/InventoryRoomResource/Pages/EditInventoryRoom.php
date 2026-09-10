<?php

namespace App\Filament\Inventory\Resources\InventoryRoomResource\Pages;

use App\Filament\Inventory\Resources\InventoryRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryRoom extends EditRecord
{
    protected static string $resource = InventoryRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return InventoryRoomResource::getUrl('view', ['record' => $this->record]);
    }

    /**
     * Downscale and rebuild the thumbnail whenever the photo changes through
     * the full form, the same as the standalone "Change photo" action does.
     */
    protected function afterSave(): void
    {
        if ($this->record->wasChanged('image')) {
            InventoryRoomResource::applyRoomPhoto($this->record, $this->record->image);
        }
    }
}
