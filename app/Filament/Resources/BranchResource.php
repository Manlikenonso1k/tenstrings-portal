<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Models\Branch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'School Management';

    protected static ?string $navigationLabel = 'Branch Pricing';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Branch Name')
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_luxury_branch')
                    ->label('Luxury Branch (Apply Markup)')
                    ->helperText('When enabled, an extra percentage will be added on top of the base course fee for students at this branch.')
                    ->live()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('markup_percentage')
                    ->label('Markup Percentage (%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01)
                    ->suffix('%')
                    ->placeholder('e.g. 10 for a 10% markup')
                    ->helperText('Leave at 0 if no markup should be applied.')
                    ->visible(fn (Get $get): bool => (bool) $get('is_luxury_branch'))
                    ->rules(['numeric', 'min:0', 'max:100'])
                    ->columnSpanFull(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Branch')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_luxury_branch')
                    ->label('Luxury Branch')
                    ->boolean()
                    ->trueColor('warning')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('markup_percentage')
                    ->label('Markup %')
                    ->formatStateUsing(fn ($state): string => $state > 0 ? number_format((float) $state, 2) . '%' : '—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Configure Markup'),
            ])
            ->paginated(false);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBranches::route('/'),
            'edit'  => Pages\EditBranch::route('/{record}/edit'),
        ];
    }

    /**
     * Only super_admin and admin may access branch pricing settings.
     */
    public static function canAccess(): bool
    {
        return in_array(Auth::user()?->role, ['super_admin', 'admin'], true);
    }

    /**
     * Branches are seeded — no creation or deletion through the UI.
     */
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
