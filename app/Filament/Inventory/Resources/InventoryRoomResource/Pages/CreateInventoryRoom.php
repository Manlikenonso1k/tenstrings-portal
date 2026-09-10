<?php

namespace App\Filament\Inventory\Resources\InventoryRoomResource\Pages;

use App\Filament\Inventory\Resources\InventoryRoomResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateInventoryRoom extends CreateRecord
{
    protected static string $resource = InventoryRoomResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        if ($user && ! $user->can('inventory.view_all_branches')) {
            $data['branch_id'] = $user->branch_id;
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return InventoryRoomResource::getUrl('view', ['record' => $this->record]);
    }

    protected function afterCreate(): void
    {
        if (filled($this->record->image)) {
            InventoryRoomResource::applyRoomPhoto($this->record, $this->record->image);
        }
    }
}
