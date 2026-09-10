<?php

namespace App\Filament\Inventory\Resources;

use App\Enums\CheckoutStatus;
use App\Filament\Inventory\Actions\CheckoutActions;
use App\Filament\Inventory\Resources\InventoryCheckoutResource\Pages;
use App\Filament\Inventory\Resources\InventoryCheckoutResource\RelationManagers;
use App\Models\Branch;
use App\Models\InventoryCheckout;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class InventoryCheckoutResource extends Resource
{
    protected static ?string $model = InventoryCheckout::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?string $navigationLabel = 'Event checkouts';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'reference';

    public static function canViewAny(): bool
    {
        return Auth::user()?->can('inventory_checkout.view') ?? false;
    }

    public static function canCreate(): bool
    {
        // Created through the wizard, never through a plain create page.
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->can('delete', $record) ?? false;
    }

    public static function getNavigationBadge(): ?string
    {
        if (! static::canViewAny()) {
            return null;
        }

        $open = static::getEloquentQuery()->whereIn('status', CheckoutStatus::open())->count();

        return $open > 0 ? (string) $open : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $overdue = static::getEloquentQuery()->where('status', CheckoutStatus::Overdue->value)->count();

        return $overdue > 0 ? 'danger' : 'warning';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['branch', 'releasedBy'])->withCount('lines'))
            ->defaultSort('checked_out_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('event_name')
                    ->label('Event')
                    ->searchable()
                    ->description(fn (InventoryCheckout $record): ?string => $record->event_venue)
                    ->wrap(),

                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('responsible_person_name')
                    ->label('Responsible person')
                    ->searchable()
                    ->description(fn (InventoryCheckout $record): ?string => $record->responsible_person_phone),

                Tables\Columns\TextColumn::make('lines_count')
                    ->label('Items')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('expected_return_at')
                    ->label('Expected return')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->color(fn (InventoryCheckout $record): ?string => $record->status !== CheckoutStatus::Returned
                        && $record->expected_return_at?->isPast() ? 'danger' : null),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('branch_id')
                    ->label('Branch')
                    ->options(fn (): array => Branch::query()->orderBy('name')->pluck('name', 'id')->all())
                    ->visible(fn (): bool => Auth::user()?->can('inventory.view_all_branches') ?? false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                CheckoutActions::returnCheckout(),
            ])
            ->bulkActions([])
            ->emptyStateHeading('No checkouts yet')
            ->emptyStateDescription('Items released for a concert or event show up here.');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LinesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryCheckouts::route('/'),
            'view' => Pages\ViewInventoryCheckout::route('/{record}'),
        ];
    }
}
