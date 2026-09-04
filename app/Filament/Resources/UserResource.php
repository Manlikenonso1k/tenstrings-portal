<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Branch;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationGroup = 'System Settings';
    protected static ?string $navigationLabel = 'User Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('phone')->tel()->maxLength(30),
            Forms\Components\Select::make('role')->required()->options(fn (): array => [
                'super_admin' => 'Super Admin',
                'admin' => 'Admin',
                'accounts_clerk' => 'Accounts Clerk',
                'instructor' => 'Instructor',
                'student' => 'Student',
                'ceo' => 'CEO (Inventory read-only)',
                'inventory_officer' => 'Inventory Officer',
                'branch_manager' => 'Branch Manager',
            ]),
            Forms\Components\Select::make('branch_id')
                ->label('Assigned Branch')
                ->options(fn (): array => Branch::query()->orderBy('name')->pluck('name', 'id')->all())
                ->searchable()
                ->preload()
                ->required(fn (Forms\Get $get): bool => in_array($get('role'), ['inventory_officer', 'branch_manager'], true))
                ->helperText('Required for Inventory Officers and Branch Managers.'),
            Forms\Components\TextInput::make('password')->password()->dehydrated(fn ($state) => filled($state))->required(fn (string $operation): bool => $operation === 'create')->minLength(8)->confirmed(),
            Forms\Components\TextInput::make('password_confirmation')->password()->dehydrated(false)->required(fn (string $operation): bool => $operation === 'create'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('email')->searchable(),
            Tables\Columns\TextColumn::make('phone'),
            Tables\Columns\BadgeColumn::make('role'),
            Tables\Columns\TextColumn::make('branch.name')->label('Assigned Branch')->toggleable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])->actions([Tables\Actions\EditAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array { return ['index' => Pages\ListUsers::route('/'), 'create' => Pages\CreateUser::route('/create'), 'edit' => Pages\EditUser::route('/{record}/edit')]; }
    public static function canAccess(): bool { return Auth::user()?->isSuperAdmin() ?? false; }
    public static function canCreate(): bool { return Auth::user()?->isSuperAdmin() ?? false; }
    public static function canDelete($record): bool { $user = Auth::user(); return $user?->isSuperAdmin() && $record->id !== $user->id; }
}