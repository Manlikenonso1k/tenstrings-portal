<?php

namespace App\Filament\Inventory\Resources\InventoryCheckoutResource\Pages;

use App\Filament\Inventory\Actions\CheckoutActions;
use App\Filament\Inventory\Resources\InventoryCheckoutResource;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewInventoryCheckout extends ViewRecord
{
    protected static string $resource = InventoryCheckoutResource::class;

    public function getTitle(): string
    {
        return (string) $this->record->reference;
    }

    public function getSubheading(): ?string
    {
        return $this->record->event_name;
    }

    protected function getHeaderActions(): array
    {
        return [
            CheckoutActions::returnCheckoutHeader(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Event')
                ->schema([
                    Infolists\Components\TextEntry::make('event_name')->label('Event'),
                    Infolists\Components\TextEntry::make('event_venue')->label('Venue')->placeholder('—'),
                    Infolists\Components\TextEntry::make('event_date')->label('Date')->date(),
                    Infolists\Components\TextEntry::make('status')->badge(),
                    Infolists\Components\TextEntry::make('branch.name')->label('Branch'),
                    Infolists\Components\TextEntry::make('notes')->placeholder('—')->columnSpanFull(),
                ])
                ->columns(3),

            Infolists\Components\Section::make('Custody')
                ->schema([
                    Infolists\Components\TextEntry::make('responsible_person_name')->label('Taken by'),
                    Infolists\Components\TextEntry::make('responsible_person_phone')->label('Phone')->placeholder('—'),
                    Infolists\Components\TextEntry::make('expected_return_at')->label('Expected return')->dateTime('d M Y H:i'),
                    Infolists\Components\TextEntry::make('releasedBy.name')->label('Released by')->placeholder('—'),
                    Infolists\Components\TextEntry::make('checked_out_at')->label('Released at')->dateTime('d M Y H:i'),
                ])
                ->columns(3),
        ]);
    }
}
