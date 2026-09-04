<?php

namespace App\Filament\Inventory\Imports;

use App\Models\Branch;
use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\InventoryRoom;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms;
use Filament\Actions\Imports\Importer;
use Illuminate\Support\Str;

class AjahInventoryImporter extends Importer
{
    protected static ?string $model = InventoryItem::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('tenstrings_office_name')->label('TENSTRINGS OFFICE NAME')->requiredMapping()->rules(['required', 'string', 'max:255']),
            ImportColumn::make('item_code')->label('ITEM CODE')->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('item')->label('ITEM')->requiredMapping()->rules(['required', 'string', 'max:255']),
        ];
    }

    public function resolveRecord(): ?InventoryItem
    {
        $branchId = (int) ($this->options['branch_id'] ?? 0);
        $assetTag = self::normaliseAssetTag((string) ($this->data['item_code'] ?? ''));

        if ($branchId <= 0) {
            throw new RowImportFailedException('Select the branch that owns this inventory file.');
        }

        if ($assetTag !== '') {
            $existing = InventoryItem::withoutGlobalScope('branch')->withTrashed()->where('asset_tag', $assetTag)->first();
            if ($existing) {
                throw new RowImportFailedException("Skipped duplicate: asset tag {$assetTag} already exists.");
            }
        }

        return new InventoryItem();
    }

    protected function beforeSave(): void
    {
        $branchId = (int) $this->options['branch_id'];
        $office = trim((string) ($this->data['tenstrings_office_name'] ?? ''));
        $rawItem = trim((string) ($this->data['item'] ?? ''));
        [$quantity, $name] = self::splitQuantityAndName($rawItem);
        $room = self::resolveRoom($branchId, $office);
        $category = self::resolveCategory($name);

        $duplicate = InventoryItem::withoutGlobalScope('branch')
            ->where('branch_id', $branchId)
            ->where('inventory_room_id', $room->id)
            ->whereRaw('LOWER(name) = ?', [Str::lower($name)])
            ->whereRaw('LOWER(COALESCE(brand, \'\')) = \'\'')
            ->exists();

        if ($duplicate) {
            throw new RowImportFailedException("Skipped duplicate: {$name} is already logged in {$room->name}.");
        }

        $this->record->fill([
            'branch_id' => $branchId,
            'inventory_room_id' => $room->id,
            'inventory_category_id' => $category->id,
            'name' => $name,
            'asset_tag' => self::normaliseAssetTag((string) ($this->data['item_code'] ?? '')) ?: null,
            'quantity' => $quantity,
            'unit' => 'unit',
            'condition' => 'good',
            'status' => 'in_use',
            'created_by' => $this->import->user_id,
            'notes' => "Imported from Ajah inventory CSV. Source office: {$office}",
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        return 'Imported ' . number_format((int) $import->successful_rows) . ' of ' . number_format((int) $import->total_rows) . ' inventory rows. Review failed rows for skipped duplicates.';
    }

    private static function resolveRoom(int $branchId, string $office): InventoryRoom
    {
        $name = $office !== '' ? $office : 'Unassigned Room';
        $code = Str::upper(Str::substr(Str::slug($name, '-'), 0, 32));

        return InventoryRoom::withoutGlobalScope('branch')->firstOrCreate(
            ['branch_id' => $branchId, 'code' => $code],
            ['name' => $name, 'room_type' => 'office', 'is_active' => true],
        );
    }

    private static function resolveCategory(string $name): InventoryCategory
    {
        $value = Str::lower($name);
        $category = match (true) {
            str_contains($value, 'monitor'), str_contains($value, 'cpu'), str_contains($value, 'laptop'), str_contains($value, 'web cam') => 'IT Equipment',
            str_contains($value, 'speaker'), str_contains($value, 'studio monitor'), str_contains($value, 'mic') => 'Audio/Visual',
            str_contains($value, 'chair'), str_contains($value, 'table'), str_contains($value, 'blind'), str_contains($value, 'frame') => 'Furniture',
            default => 'Office Supplies',
        };

        return InventoryCategory::firstOrCreate(['slug' => Str::slug($category)], ['name' => $category, 'is_active' => true]);
    }

    private static function splitQuantityAndName(string $item): array
    {
        if (preg_match('/^(\d+)\s+(.+)$/', $item, $matches)) {
            return [max(1, (int) $matches[1]), trim($matches[2])];
        }

        return [1, $item];
    }

    private static function normaliseAssetTag(string $tag): string
    {
        return preg_replace('/\s*-\s*/', '-', trim($tag)) ?: '';
    }
}