<?php

namespace App\Filament\Inventory\Resources\InventoryRoomResource\Pages;

use App\Filament\Inventory\Resources\InventoryRoomResource;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewInventoryRoom extends ViewRecord
{
    protected static string $resource = InventoryRoomResource::class;

    public function getTitle(): string
    {
        return (string) $this->record->name;
    }

    public function getSubheading(): ?string
    {
        return $this->record->branch?->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('backToRooms')
                ->label('All rooms')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->outlined()
                ->url(InventoryRoomResource::getUrl('index')),

            Actions\Action::make('changePhoto')
                ->label('Change photo')
                ->icon('heroicon-o-camera')
                ->color('gray')
                ->modalHeading(fn (): string => 'Change photo — ' . $this->record->name)
                ->modalSubmitActionLabel('Save photo')
                ->visible(fn (): bool => Auth::user()?->can('updatePhoto', $this->record) ?? false)
                ->fillForm(fn (): array => ['image' => $this->record->image])
                ->form([InventoryRoomResource::photoField()->required()])
                ->action(function (array $data): void {
                    InventoryRoomResource::applyRoomPhoto($this->record, $data['image'] ?? null);
                    $this->refreshFormData(['image']);
                })
                ->successNotificationTitle('Room photo updated'),

            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Split::make([
                Infolists\Components\ImageEntry::make('image')
                    ->hiddenLabel()
                    ->disk('public')
                    ->height(180)
                    ->extraImgAttributes(['class' => 'rounded-lg object-cover'])
                    ->defaultImageUrl(fn (): string => InventoryRoomResource::initialPlaceholder($this->record))
                    ->grow(false),

                Infolists\Components\Grid::make(2)->schema([
                    Infolists\Components\TextEntry::make('code')->label('Room code'),
                    Infolists\Components\TextEntry::make('room_type')->label('Type')->badge(),
                    Infolists\Components\TextEntry::make('floor')->placeholder('—'),
                    Infolists\Components\TextEntry::make('last_audited_at')
                        ->label('Last audited')
                        ->date()
                        ->placeholder('Never')
                        ->color(fn (): ?string => $this->record->isAuditStale() ? 'warning' : null),
                    Infolists\Components\TextEntry::make('items_total')
                        ->label('Items')
                        ->state(fn (): int => $this->record->items()->count()),
                    Infolists\Components\TextEntry::make('quantity_total')
                        ->label('Total quantity')
                        ->state(fn (): int => (int) $this->record->items()->sum('quantity')),
                    Infolists\Components\TextEntry::make('description')
                        ->placeholder('—')
                        ->columnSpanFull(),
                ]),
            ])->from('md'),
        ]);
    }
}
