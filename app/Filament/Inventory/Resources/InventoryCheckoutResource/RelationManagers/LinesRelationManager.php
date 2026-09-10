<?php

namespace App\Filament\Inventory\Resources\InventoryCheckoutResource\RelationManagers;

use App\Models\InventoryCheckoutItem;
use App\Models\InventoryCheckoutPhoto;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

/**
 * The evidence trail: every line with its before and after photos side by side.
 */
class LinesRelationManager extends RelationManager
{
    protected static string $relationship = 'lines';

    protected static ?string $title = 'Items on this checkout';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['item', 'photos', 'receivedBy']))
            ->columns([
                Tables\Columns\TextColumn::make('item.name')
                    ->label('Item')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (InventoryCheckoutItem $record): ?string => $record->item?->asset_tag),

                Tables\Columns\TextColumn::make('quantity')->label('Out')->alignCenter(),

                Tables\Columns\TextColumn::make('returned_quantity')
                    ->label('Back')
                    ->alignCenter()
                    ->color(fn (InventoryCheckoutItem $record): ?string => $record->isFullyReturned() ? 'success' : 'warning'),

                Tables\Columns\ImageColumn::make('photos_out')
                    ->label('Before')
                    ->disk('public')
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->state(fn (InventoryCheckoutItem $record): array => $record->photos
                        ->where('stage.value', 'out')
                        ->pluck('path')
                        ->all()),

                Tables\Columns\ImageColumn::make('photos_in')
                    ->label('After')
                    ->disk('public')
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->state(fn (InventoryCheckoutItem $record): array => $record->photos
                        ->where('stage.value', 'in')
                        ->pluck('path')
                        ->all()),

                Tables\Columns\TextColumn::make('condition_out')->label('Went out')->badge(),

                Tables\Columns\TextColumn::make('condition_in')->label('Came back')->badge()->placeholder('—'),

                Tables\Columns\TextColumn::make('return_status')->label('Result')->badge()->placeholder('Still out'),

                Tables\Columns\TextColumn::make('receivedBy.name')->label('Received by')->placeholder('—')->toggleable(),

                Tables\Columns\TextColumn::make('returned_at')->label('Returned at')->dateTime('d M Y H:i')->placeholder('—')->toggleable(),
            ])
            ->headerActions([])
            ->actions([
                // Confirmed photos are evidence. Only an admin can touch them,
                // and the change is written to the activity log.
                Tables\Actions\Action::make('managePhotos')
                    ->label('Manage photos')
                    ->icon('heroicon-o-photo')
                    ->color('gray')
                    ->visible(fn (): bool => Auth::user()?->can('managePhotos', $this->getOwnerRecord()) ?? false)
                    ->form(fn (InventoryCheckoutItem $record): array => [
                        Forms\Components\CheckboxList::make('remove')
                            ->label('Remove these photos')
                            ->options($record->photos
                                ->mapWithKeys(fn (InventoryCheckoutPhoto $photo): array => [
                                    $photo->getKey() => ucfirst($photo->stage->value) . ' — ' . basename($photo->path),
                                ])
                                ->all())
                            ->columns(1),
                        Forms\Components\Textarea::make('reason')
                            ->label('Why')
                            ->required()
                            ->helperText('Recorded in the activity log against this checkout.'),
                    ])
                    ->requiresConfirmation()
                    ->modalDescription('Removing evidence photos is logged with your name.')
                    ->action(function (InventoryCheckoutItem $record, array $data): void {
                        $ids = array_map('intval', $data['remove'] ?? []);

                        if ($ids === []) {
                            return;
                        }

                        $photos = $record->photos()->whereIn('id', $ids)->get();

                        activity('inventory_checkouts')
                            ->performedOn($this->getOwnerRecord())
                            ->causedBy(Auth::user())
                            ->withProperties([
                                'line_id' => $record->getKey(),
                                'removed_paths' => $photos->pluck('path')->all(),
                                'reason' => $data['reason'],
                            ])
                            ->log('Removed checkout evidence photos');

                        $record->photos()->whereIn('id', $ids)->delete();
                    })
                    ->successNotificationTitle('Photos removed and logged'),
            ])
            ->bulkActions([])
            ->paginated(false);
    }
}
