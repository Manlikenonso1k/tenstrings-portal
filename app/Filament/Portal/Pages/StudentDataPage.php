<?php

namespace App\Filament\Portal\Pages;

use Filament\Pages\Page;

class StudentDataPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'STUDENT DATA';

    protected static string $view = 'filament.portal.pages.student-data-page';
}
