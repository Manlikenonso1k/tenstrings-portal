<?php

namespace App\Filament\Portal\Pages;

use Filament\Pages\Page;

class AccommodationPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'ACCOMMODATION';

    protected static string $view = 'filament.portal.pages.accommodation-page';
}
