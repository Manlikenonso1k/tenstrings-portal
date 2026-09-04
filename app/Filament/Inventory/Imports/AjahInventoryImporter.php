<?php

namespace App\Filament\Inventory\Imports;

use App\Models\Branch;
use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\InventoryRoom;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms;
use Illuminate\Support\Str;

class AjahInventoryImporter extends Importer
{
    protected static ?string $model = InventoryItem::class;

    /** Import immediately; this inventory sheet is small and staff need the result before leaving the page. */
    public function getJobConnection(): ?string
    {
        return 'sync';
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Forms\Components\Select::make('branch_id')
                ->label('Branch for this file')
                ->options(function (): array {
                    $user = auth()->user();
                    if (! $user) { return []; }
                    if ($user->can('inventory.view_all_branches')) {
                        return Branch::query()->where('is_active', true)->orderBy('name')->pluck('name', 'id')->all();
                    }
                    return Branch::query()->whereKey($user->branch_id)->pluck('name', 'id')->all();
                })
                ->default(fn (): ?int => auth()->user()?->branch_id ?: Branch::query()->where('slug', 'ajah-branch')->value('id'))
                ->disabled(fn (): bool => ! (auth()->user()?->can('inventory.view_all_branches') ?? false))
                ->dehydrated()
                ->required(),
        ];
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('tenstrings_office_name')->label('TENSTRINGS OFFICE NAME')->requiredMapping()->rules(['required', 'string', 'max:255'])->fillRecordUsing(fn (): null => null),
            ImportColumn::make('item_code')->label('ITEM CODE')->rules(['nullable', 'string', 'max:255'])->fillRecordUsing(fn (): null => null),
            ImportColumn::make('item')->label('ITEM')->requiredMapping()->rules(['required', 'string', 'max:255'])->fillRecordUsing(fn (): null => null),
        ];
    }

    public function resolveRecord(): ?InventoryItem
    {
        $branchId = (int) ($this->getOptions()['branch_id'] ?? 0);
        $assetTag = self::normaliseAssetTag((string) ($this->data['item_code'] ?? ''));
        if ($branchId <= 0) { throw new RowImportFailedException('Select the branch that owns this inventory file.'); }
        if ($assetTag !== '' && InventoryItem::withoutGlobalScope('branch')->withTrashed()->where('asset_tag', $assetTag)->exists()) {
            throw new RowImportFailedException("Skipped duplicate: asset tag {$assetTag} already exists.");
        }
        return new InventoryItem();
    }

    protected function beforeSave(): void
    {
        $branchId = (int) $this->getOptions()['branch_id'];
        $office = trim((string) ($this->data['tenstrings_office_name'] ?? ''));
        [$quantity, $name] = self::splitQuantityAndName(trim((string) ($this->data['item'] ?? '')));
        $room = self::resolveRoom($branchId, $office);
        $category = self::resolveCategory($name);
        if (InventoryItem::withoutGlobalScope('branch')->where('branch_id', $branchId)->where('inventory_room_id', $room->id)->whereRaw('LOWER(name) = ?', [Str::lower($name)])->exists()) {
            throw new RowImportFailedException("Skipped duplicate: {$name} is already logged in {$room->name}.");
        }
        $this->record->fill(['branch_id' => $branchId, 'inventory_room_id' => $room->id, 'inventory_category_id' => $category->id, 'name' => $name, 'asset_tag' => self::normaliseAssetTag((string) ($this->data['item_code'] ?? '')) ?: null, 'quantity' => $quantity, 'unit' => 'unit', 'condition' => 'good', 'status' => 'in_use', 'created_by' => $this->import->user_id, 'notes' => "Imported inventory CSV. Source office: {$office}"]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        return 'Imported ' . number_format((int) $import->successful_rows) . ' of ' . number_format((int) $import->total_rows) . ' inventory rows. Review failed rows for skipped duplicates.';
    }

    private static function resolveRoom(int $branchId, string $office): InventoryRoom
    {
        $name = $office !== '' ? $office : 'Unassigned Room';
        return InventoryRoom::withoutGlobalScope('branch')->firstOrCreate(['branch_id' => $branchId, 'code' => Str::upper(Str::substr(Str::slug($name, '-'), 0, 32))], ['name' => $name, 'room_type' => 'office', 'is_active' => true]);
    }

    private static function resolveCategory(string $name): InventoryCategory
    {
        $value = Str::lower($name);
        $category = match (true) { str_contains($value, 'monitor'), str_contains($value, 'cpu'), str_contains($value, 'laptop'), str_contains($value, 'web cam') => 'IT Equipment', str_contains($value, 'speaker'), str_contains($value, 'studio monitor'), str_contains($value, 'mic') => 'Audio/Visual', str_contains($value, 'chair'), str_contains($value, 'table'), str_contains($value, 'blind'), str_contains($value, 'frame') => 'Furniture', default => 'Office Supplies' };
        return InventoryCategory::firstOrCreate(['slug' => Str::slug($category)], ['name' => $category, 'is_active' => true]);
    }

    private static function splitQuantityAndName(string $item): array { return preg_match('/^(\d+)\s+(.+)$/', $item, $matches) ? [max(1, (int) $matches[1]), trim($matches[2])] : [1, $item]; }
    private static function normaliseAssetTag(string $tag): string { return preg_replace('/\s*-\s*/', '-', trim($tag)) ?: ''; }
}