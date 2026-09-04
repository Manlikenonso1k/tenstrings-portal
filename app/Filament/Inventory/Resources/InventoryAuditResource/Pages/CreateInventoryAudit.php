<?php
namespace App\Filament\Inventory\Resources\InventoryAuditResource\Pages;
use App\Filament\Inventory\Resources\InventoryAuditResource;
use App\Models\InventoryAuditLine;
use App\Models\InventoryItem;
use Filament\Resources\Pages\CreateRecord;
class CreateInventoryAudit extends CreateRecord {
 protected static string $resource = InventoryAuditResource::class;
 protected function mutateFormDataBeforeCreate(array $data): array { if (! auth()->user()?->can('inventory.view_all_branches')) { $data['branch_id'] = auth()->user()?->branch_id; } $data['conducted_by'] = auth()->id(); $data['started_at'] = now(); $data['status'] = $data['status'] ?? 'draft'; return $data; }
 protected function afterCreate(): void { $query = InventoryItem::query()->where('branch_id',$this->record->branch_id); if ($this->record->inventory_room_id) { $query->where('inventory_room_id',$this->record->inventory_room_id); } $query->get()->each(fn (InventoryItem $item) => InventoryAuditLine::firstOrCreate(['inventory_audit_id'=>$this->record->id,'inventory_item_id'=>$item->id],['expected_quantity'=>$item->quantity,'condition_found'=>$item->condition])); }
}