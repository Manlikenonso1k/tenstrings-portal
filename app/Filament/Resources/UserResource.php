<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Access Control';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('phone')->tel()->maxLength(30),
            Forms\Components\Select::make('role')
                ->required()
                ->options(fn () => auth()->user()?->isSuperAdmin()
                    ? [
                        'super_admin' => 'Super Admin',
                        'admin' => 'Admin',
                        'instructor' => 'Instructor',
                        'student' => 'Student',
                    ]
                    : [
                        'admin' => 'Admin',
                        'instructor' => 'Instructor',
                        'student' => 'Student',
                    ]
                ),
            Forms\Components\TextInput::make('password')
                ->password()
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $operation): bool => $operation === 'create')
                ->minLength(8)
                ->confirmed(),
            Forms\Components\TextInput::make('password_confirmation')
                ->password()
                ->dehydrated(false)
                ->required(fn (string $operation): bool => $operation === 'create'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\BadgeColumn::make('role'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canDelete($record): bool
    {
        $authUser = auth()->user();

        if (! $authUser || ! $authUser->isAdmin()) {
            return false;
        }

        if ($record->id === $authUser->id) {
            return false;
        }

        return $authUser->isSuperAdmin() || $record->role !== 'super_admin';
    }
}
