<?php
namespace App\Filament\Inventory\Resources\InventoryItemResource\Pages;
use App\Filament\Inventory\Resources\InventoryItemResource;
use Filament\Resources\Pages\CreateRecord;
class CreateInventoryItem extends CreateRecord
{
    protected static string $resource = InventoryItemResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        if ($user && ! $user->can('inventory.view_all_branches')) { $data['branch_id'] = $user->branch_id; }
        return $data;
    }
}