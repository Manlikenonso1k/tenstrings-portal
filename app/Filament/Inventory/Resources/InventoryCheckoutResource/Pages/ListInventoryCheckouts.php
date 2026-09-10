<?php

namespace App\Filament\Inventory\Resources\InventoryCheckoutResource\Pages;

use App\Enums\CheckoutStatus;
use App\Filament\Inventory\Actions\CheckoutActions;
use App\Filament\Inventory\Resources\InventoryCheckoutResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListInventoryCheckouts extends ListRecords
{
    protected static string $resource = InventoryCheckoutResource::class;

    public function getTitle(): string
    {
        return 'Event checkouts';
    }

    protected function getHeaderActions(): array
    {
        return [
            CheckoutActions::newCheckout(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'out' => Tab::make('Out')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', [
                    CheckoutStatus::Out->value,
                    CheckoutStatus::PartiallyReturned->value,
                ])),

            'overdue' => Tab::make('Overdue')
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CheckoutStatus::Overdue->value)),

            'returned' => Tab::make('Returned')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CheckoutStatus::Returned->value)),

            'all' => Tab::make('All'),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 'out';
    }
}
