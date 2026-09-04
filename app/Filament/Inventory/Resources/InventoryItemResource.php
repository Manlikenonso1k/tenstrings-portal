<?php

namespace App\Filament\Inventory\Resources;

use App\Enums\ItemCondition;
use App\Enums\ItemStatus;
use App\Filament\Inventory\Resources\InventoryItemResource\Pages;
use App\Models\InventoryItem;
use App\Models\InventoryTransfer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InventoryItemResource extends Resource
{
    protected static ?string $model = InventoryItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Inventory';

    public static function canViewAny(): bool { return auth()->user()?->can('item.view') ?? false; }
    public static function canCreate(): bool { return auth()->user()?->can('item.create') ?? false; }
    public static function canEdit($record): bool { return auth()->user()?->can('update', $record) ?? false; }
    public static function canDelete($record): bool { return auth()->user()?->can('delete', $record) ?? false; }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identification')->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('asset_tag')->helperText('Leave blank to generate automatically.'),
                Forms\Components\Select::make('inventory_category_id')->relationship('category', 'name')->required(),
                Forms\Components\TextInput::make('brand'), Forms\Components\TextInput::make('model'), Forms\Components\TextInput::make('serial_number'),
            ])->columns(2),
            Forms\Components\Section::make('Location')->schema([
                Forms\Components\Select::make('branch_id')->relationship('branch', 'name')->required()->default(fn (): ?int => auth()->user()?->branch_id)->disabled(fn (): bool => ! (auth()->user()?->can('inventory.view_all_branches') ?? false))->dehydrated(),
                Forms\Components\Select::make('inventory_room_id')->relationship('room', 'name')->searchable()->preload(),
                Forms\Components\TextInput::make('quantity')->numeric()->minValue(0)->default(1)->required(), Forms\Components\TextInput::make('unit')->default('unit')->required(),
            ])->columns(2),
            Forms\Components\Section::make('Condition & status')->schema([
                Forms\Components\Select::make('condition')->options(ItemCondition::options())->default('good')->required(), Forms\Components\Select::make('status')->options(ItemStatus::options())->default('in_use')->required(), Forms\Components\DatePicker::make('last_verified_at'),
            ])->columns(2),
            Forms\Components\Section::make('Acquisition')->visible(fn (): bool => auth()->user()?->can('inventory.view_costs') ?? false)->schema([
                Forms\Components\DatePicker::make('acquisition_date'), Forms\Components\TextInput::make('purchase_cost')->numeric()->prefix('?'), Forms\Components\TextInput::make('supplier'),
            ])->columns(3),
            Forms\Components\Section::make('Photo & notes')->schema([
                Forms\Components\FileUpload::make('photo_path')->image()->imageEditor()->maxSize((int) config('inventory.photo_max_size'))->extraAttributes(['capture' => 'environment']), Forms\Components\Textarea::make('notes')->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid(['md' => 2, 'xl' => 4])
            ->groups([Group::make('room.name')->label('Room')->collapsible()])
            ->defaultGroup('room.name')
            ->columns([
                Tables\Columns\ImageColumn::make('photo_path')->label('Photo')->disk(config('filesystems.default'))->square()->defaultImageUrl(url('/images/placeholder-item.png')),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('asset_tag')->copyable()->placeholder('Auto-generated'),
                Tables\Columns\TextColumn::make('category.name')->label('Category')->badge(),
                Tables\Columns\TextColumn::make('quantity')->suffix(fn (InventoryItem $record): string => ' ' . $record->unit),
                Tables\Columns\TextColumn::make('condition')->badge(), Tables\Columns\TextColumn::make('status')->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('branch')->relationship('branch', 'name'), Tables\Filters\SelectFilter::make('room')->relationship('room', 'name'), Tables\Filters\SelectFilter::make('category')->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('condition')->options(ItemCondition::options()), Tables\Filters\SelectFilter::make('status')->options(ItemStatus::options()),
                Tables\Filters\TernaryFilter::make('needs_attention')->queries(true: fn (Builder $q) => $q->needsAttention(), false: fn (Builder $q) => $q),
            ])
            ->actions([
                Tables\Actions\Action::make('transfer')
                    ->label('Transfer')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('warning')
                    ->visible(fn (InventoryItem $record): bool => auth()->user()?->can('transfer', $record) ?? false)
                    ->form([
                        Forms\Components\TextInput::make('destination')->label('Destination / event')->required()->maxLength(255),
                        Forms\Components\Select::make('type')->options(['internal' => 'Internal transfer', 'external_event' => 'External event', 'return' => 'Return'])->required(),
                        Forms\Components\DatePicker::make('date')->default(now())->required(),
                    ])
                    ->action(function (InventoryItem $record, array $data): void {
                        InventoryTransfer::create(['item_id' => $record->id, 'destination' => $data['destination'], 'type' => $data['type'], 'date' => $data['date']]);
                        $record->update(['status' => $data['type'] === 'return' ? ItemStatus::InUse : ItemStatus::InStorage]);
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array { return ['index' => Pages\ListInventoryItems::route('/'), 'create' => Pages\CreateInventoryItem::route('/create'), 'edit' => Pages\EditInventoryItem::route('/{record}/edit')]; }
}