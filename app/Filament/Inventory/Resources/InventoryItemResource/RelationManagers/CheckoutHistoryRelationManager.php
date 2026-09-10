<?php

namespace App\Filament\Inventory\Resources\InventoryItemResource\RelationManagers;

use App\Filament\Inventory\Resources\InventoryCheckoutResource;
use App\Models\InventoryCheckoutItem;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

/**
 * Every event this item has been to.
 */
class CheckoutHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'checkoutLines';

    protected static ?string $title = 'Checkout history';

    public static function canViewForRecord($ownerRecord, string $pageClass): bool
    {
        return Auth::user()?->can('inventory_checkout.view') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['checkout', 'photos']))
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('checkout.reference')->label('Reference')->searchable(),
                Tables\Columns\TextColumn::make('checkout.event_name')->label('Event')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('checkout.event_date')->label('Date')->date()->sortable(),
                Tables\Columns\TextColumn::make('quantity')->label('Out'),
                Tables\Columns\TextColumn::make('returned_quantity')->label('Back'),
                Tables\Columns\TextColumn::make('condition_out')->label('Went out')->badge(),
                Tables\Columns\TextColumn::make('condition_in')->label('Came back')->badge()->placeholder('—'),
                Tables\Columns\TextColumn::make('return_status')->label('Result')->badge()->placeholder('Still out'),
                Tables\Columns\TextColumn::make('checkout.status')->label('Checkout')->badge(),
            ])
            ->actions([
                Tables\Actions\Action::make('openCheckout')
                    ->label('Open')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (InventoryCheckoutItem $record): ?string => $record->checkout
                        ? InventoryCheckoutResource::getUrl('view', ['record' => $record->checkout])
                        : null),
            ])
            ->bulkActions([])
            ->emptyStateHeading('This item has not left for an event');
    }
}
