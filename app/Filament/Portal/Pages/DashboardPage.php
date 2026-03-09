<?php

namespace App\Filament\Portal\Pages;

use Filament\Pages\Page;

class DashboardPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'DASHBOARD';

    protected static string $view = 'filament.portal.pages.dashboard-page';
}
