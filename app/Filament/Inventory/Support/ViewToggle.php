<?php

namespace App\Filament\Inventory\Support;

use App\Filament\Inventory\Resources\InventoryItemResource;
use App\Filament\Inventory\Resources\InventoryRoomResource;
use Filament\Actions\Action;

/**
 * The Rooms / All items switch. Filament tabs cannot swap between two models,
 * so the two views stay two pages and this pair of links joins them.
 */
class ViewToggle
{
    /**
     * @return array<int, Action>
     */
    public static function make(string $active): array
    {
        return [
            Action::make('viewRooms')
                ->label('Rooms')
                ->icon('heroicon-o-squares-2x2')
                ->color($active === 'rooms' ? 'primary' : 'gray')
                ->outlined($active !== 'rooms')
                ->url(InventoryRoomResource::getUrl('index')),

            Action::make('viewAllItems')
                ->label('All items')
                ->icon('heroicon-o-list-bullet')
                ->color($active === 'items' ? 'primary' : 'gray')
                ->outlined($active !== 'items')
                ->url(InventoryItemResource::getUrl('index')),
        ];
    }
}
