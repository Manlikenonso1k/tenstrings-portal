<?php

namespace App\Filament\Inventory\Resources;

use App\Enums\CheckoutStatus;
use App\Enums\RoomType;
use App\Filament\Inventory\Resources\InventoryRoomResource\Pages;
use App\Filament\Inventory\Resources\InventoryRoomResource\RelationManagers;
use App\Models\Branch;
use App\Models\InventoryCheckoutItem;
use App\Models\InventoryRoom;
use App\Support\ImageResizer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class InventoryRoomResource extends Resource
{
    protected static ?string $model = InventoryRoom::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?string $navigationLabel = 'Rooms';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        return Auth::user()?->can('room.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->can('room.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->can('update', $record) ?? false;
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->can('delete', $record) ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->helperText('Unique within the branch.'),
                Forms\Components\TextInput::make('floor'),
                Forms\Components\Select::make('room_type')
                    ->options(RoomType::options())
                    ->required(),
                Forms\Components\Toggle::make('is_active')->default(true),
                static::photoField()
                    ->columnSpanFull()
                    ->visible(fn (?InventoryRoom $record): bool => $record === null
                        || (Auth::user()?->can('updatePhoto', $record) ?? false)),
                Forms\Components\Textarea::make('description')->columnSpanFull(),
            ])
            ->columns(2);
    }

    /**
     * Shared by the edit form and the standalone "Change photo" action, so the
     * upload rules only exist in one place.
     */
    public static function photoField(): Forms\Components\FileUpload
    {
        return Forms\Components\FileUpload::make('image')
            ->label('Room photo')
            ->disk('public')
            ->directory('inventory/rooms')
            ->image()
            ->imageEditor()
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->maxSize((int) config('inventory.room_photo_max_size', 5120))
            ->helperText('Up to 5 MB. Downscaled to '
                . config('inventory.room_photo_width', 1600) . 'px on save.')
            ->extraAttributes(['capture' => 'environment']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->with('branch')
                ->withCount('items')
                ->withSum('items', 'quantity')
                ->addSelect(['event_out_count' => InventoryCheckoutItem::query()
                    ->selectRaw('COALESCE(SUM(inventory_checkout_items.quantity - inventory_checkout_items.returned_quantity), 0)')
                    ->join('inventory_items', 'inventory_items.id', '=', 'inventory_checkout_items.inventory_item_id')
                    ->join('inventory_checkouts', 'inventory_checkouts.id', '=', 'inventory_checkout_items.inventory_checkout_id')
                    ->whereColumn('inventory_items.inventory_room_id', 'inventory_rooms.id')
                    ->whereNull('inventory_items.deleted_at')
                    ->whereNull('inventory_checkouts.deleted_at')
                    ->whereIn('inventory_checkouts.status', CheckoutStatus::open())
                    ->whereColumn('inventory_checkout_items.returned_quantity', '<', 'inventory_checkout_items.quantity'),
                ]))
            ->contentGrid(['default' => 1, 'md' => 2, 'xl' => 3, '2xl' => 4])
            ->paginationPageOptions([12, 24, 48])
            ->defaultPaginationPageOption(12)
            ->recordUrl(fn (InventoryRoom $record): string => Pages\ViewInventoryRoom::getUrl(['record' => $record]))
            ->columns([
                Stack::make([
                    Tables\Columns\ImageColumn::make('image')
                        ->disk('public')
                        ->height(190)
                        ->extraImgAttributes(['class' => 'w-full h-full object-cover'])
                        ->extraAttributes(['class' => 'w-full overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800'])
                        ->defaultImageUrl(fn (InventoryRoom $record): string => static::initialPlaceholder($record)),

                    Tables\Columns\TextColumn::make('name')
                        ->weight('bold')
                        ->size('lg')
                        ->searchable()
                        ->extraAttributes(['class' => 'pt-3']),

                    Tables\Columns\TextColumn::make('branch.name')
                        ->color('gray')
                        ->size('sm'),

                    Tables\Columns\TextColumn::make('items_count')
                        ->formatStateUsing(fn ($state): string => trans_choice(':count item|:count items', (int) $state, ['count' => (int) $state]))
                        ->color('gray')
                        ->size('sm'),

                    Tables\Columns\TextColumn::make('event_out_count')
                        ->badge()
                        ->color('warning')
                        ->formatStateUsing(fn ($state): string => $state . ' out at events')
                        ->visible(fn (?InventoryRoom $record): bool => (int) ($record?->event_out_count ?? 0) > 0),
                ])->space(1),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('branch_id')
                    ->label('Branch')
                    ->options(fn (): array => Branch::query()->orderBy('name')->pluck('name', 'id')->all())
                    ->visible(fn (): bool => Auth::user()?->can('inventory.view_all_branches') ?? false),
                Tables\Filters\SelectFilter::make('room_type')
                    ->label('Room type')
                    ->options(RoomType::options()),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('open')
                        ->label('Open room')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->url(fn (InventoryRoom $record): string => Pages\ViewInventoryRoom::getUrl(['record' => $record])),
                    static::changePhotoAction(),
                    Tables\Actions\EditAction::make(),
                ])->label('Actions'),
            ])
            ->bulkActions([])
            ->emptyStateHeading('No rooms yet')
            ->emptyStateDescription('Add a room to start mapping what is in it.');
    }

    /**
     * A dedicated photo action, so a branch manager can replace a room photo
     * without being handed the full edit form.
     */
    public static function changePhotoAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('changePhoto')
            ->label('Change photo')
            ->icon('heroicon-o-camera')
            ->modalHeading(fn (InventoryRoom $record): string => 'Change photo — ' . $record->name)
            ->modalSubmitActionLabel('Save photo')
            ->authorize(fn (InventoryRoom $record): bool => Auth::user()?->can('updatePhoto', $record) ?? false)
            ->fillForm(fn (InventoryRoom $record): array => ['image' => $record->image])
            ->form([static::photoField()->required()])
            ->action(function (InventoryRoom $record, array $data): void {
                static::applyRoomPhoto($record, $data['image'] ?? null);
            })
            ->successNotificationTitle('Room photo updated');
    }

    /**
     * Downscale on the server and build the grid thumbnail. Filament's
     * imageResize options only run in the browser, which a direct upload or a
     * flaky phone can skip.
     */
    public static function applyRoomPhoto(InventoryRoom $record, ?string $path): void
    {
        if (blank($path)) {
            $record->forceFill(['image' => null, 'image_thumb_path' => null])->save();

            return;
        }

        ImageResizer::downscale($path, 'public', (int) config('inventory.room_photo_width', 1600));
        $thumb = ImageResizer::thumbnail($path, 'public', (int) config('inventory.room_thumb_width', 600));

        $record->forceFill([
            'image' => $path,
            'image_thumb_path' => $thumb,
        ])->save();
    }

    /**
     * Neutral placeholder carrying the room's initial, as an inline SVG so the
     * grid needs no extra asset and no extra request.
     */
    public static function initialPlaceholder(InventoryRoom $record): string
    {
        $initial = strtoupper(mb_substr(trim((string) $record->name) ?: '?', 0, 1));

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300">'
            . '<rect width="400" height="300" fill="#1f2937"/>'
            . '<text x="200" y="200" font-family="system-ui,sans-serif" font-size="140" font-weight="600"'
            . ' fill="#4b5563" text-anchor="middle">' . htmlspecialchars($initial, ENT_QUOTES) . '</text>'
            . '</svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryRooms::route('/'),
            'create' => Pages\CreateInventoryRoom::route('/create'),
            'view' => Pages\ViewInventoryRoom::route('/{record}'),
            'edit' => Pages\EditInventoryRoom::route('/{record}/edit'),
        ];
    }
}
