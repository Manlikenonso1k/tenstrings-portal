<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoginSessionResource\Pages;
use App\Models\LoginSession;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class LoginSessionResource extends Resource
{
    protected static ?string $model = LoginSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'System Settings';

    protected static ?string $navigationLabel = 'Session Logs';

    protected static ?int $navigationSort = 91;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('login_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.role')
                    ->label('Role')
                    ->badge(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('user_agent')
                    ->label('Device')
                    ->limit(60)
                    ->tooltip(fn (LoginSession $record): ?string => $record->user_agent),
                Tables\Columns\TextColumn::make('login_at')
                    ->label('Login At')
                    ->dateTime('M d, Y H:i:s')
                    ->sortable(),
                Tables\Columns\TextColumn::make('logout_at')
                    ->label('Logout At')
                    ->dateTime('M d, Y H:i:s')
                    ->placeholder('Active')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('last_seen')
                    ->label('Last Seen')
                    ->state(fn (LoginSession $record): string => $record->last_seen_label)
                    ->colors([
                        'success' => fn (string $state): bool => $state === 'Active now',
                        'gray' => fn (string $state): bool => $state !== 'Active now',
                    ]),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoginSessions::route('/'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->isSuperAdmin();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
