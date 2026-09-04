<?php
namespace App\Filament\Inventory\Resources\InventoryRoomResource\Pages;
use App\Filament\Inventory\Resources\InventoryRoomResource;
use Filament\Resources\Pages\CreateRecord;
class CreateInventoryRoom extends CreateRecord
{
    protected static string $resource = InventoryRoomResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        if ($user && ! $user->can('inventory.view_all_branches')) { $data['branch_id'] = $user->branch_id; }
        return $data;
    }
}