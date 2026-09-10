<?php

namespace App\Filament\Inventory\Resources\InventoryRoomResource\RelationManagers;

use App\Filament\Inventory\Actions\CheckoutActions;
use App\Filament\Inventory\Resources\InventoryItemResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Items in this room';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return InventoryItemResource::form($form);
    }

    public function table(Table $table): Table
    {
        return $table
            // Location columns are redundant here — the room is the page.
            ->columns(InventoryItemResource::itemColumns(withLocation: false))
            ->filters(InventoryItemResource::itemFilters(withLocation: false))
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add item')
                    ->visible(fn (): bool => Auth::user()?->can('item.create') ?? false)
                    // The relation manager sets inventory_room_id; branch has to
                    // follow the room, not the acting user.
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['branch_id'] = $this->getOwnerRecord()->branch_id;

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    CheckoutActions::checkoutItem(),
                    CheckoutActions::returnItem(),
                    Tables\Actions\EditAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    CheckoutActions::checkoutSelected(),
                ]),
            ])
            ->emptyStateHeading('Nothing logged in this room yet');
    }
}
