<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PortalSettingResource\Pages;
use App\Models\PortalSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PortalSettingResource extends Resource
{
    protected static ?string $model = PortalSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Access Control';

    protected static ?string $navigationLabel = 'Portal Settings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('matric_pattern')
                ->required()
                ->helperText('Tokens: {yyyy}, {yy}, {ycode}, {seq:N}. Example: {ycode}{seq:8} => 1700000001 for 2017.'),
            Forms\Components\TextInput::make('next_sequence')
                ->required()
                ->numeric()
                ->minValue(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('matric_pattern')->wrap(),
                Tables\Columns\TextColumn::make('next_sequence'),
                Tables\Columns\TextColumn::make('updated_at')->since(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPortalSettings::route('/'),
            'create' => Pages\CreatePortalSetting::route('/create'),
            'edit' => Pages\EditPortalSetting::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canCreate(): bool
    {
        return (auth()->user()?->isSuperAdmin() ?? false) && PortalSetting::query()->count() === 0;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }
}
