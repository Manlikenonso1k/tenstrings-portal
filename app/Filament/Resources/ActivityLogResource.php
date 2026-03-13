<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'System Settings';

    protected static ?string $navigationLabel = 'Activity Logs';

    protected static ?int $navigationSort = 90;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Who')
                    ->placeholder('System')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('What')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('event')
                    ->colors([
                        'success' => fn (?string $state): bool => str_contains((string) $state, 'create') || str_contains((string) $state, 'import'),
                        'warning' => fn (?string $state): bool => str_contains((string) $state, 'update'),
                        'danger' => fn (?string $state): bool => str_contains((string) $state, 'delete') || str_contains((string) $state, 'fail'),
                    ]),
                Tables\Columns\TextColumn::make('log_name')->label('Log'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('When')
                    ->dateTime('M d, Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('actor_role')
                    ->label('Actor Role')
                    ->form([
                        Select::make('actor_role')
                            ->label('Role')
                            ->options([
                                'super_admin' => 'Super Admin',
                                'admin' => 'Admin',
                                'instructor' => 'Instructor',
                                'student' => 'Student',
                                'system' => 'System',
                            ])
                            ->placeholder('All roles'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $role = $data['actor_role'] ?? null;

                        if (! $role) {
                            return $query;
                        }

                        if ($role === 'system') {
                            return $query->whereNull('causer_id');
                        }

                        return $query
                            ->where('causer_type', User::class)
                            ->whereHas('causer', fn (Builder $causerQuery) => $causerQuery->where('role', $role));
                    }),
                Tables\Filters\Filter::make('action_type')
                    ->label('Action Type')
                    ->form([
                        Select::make('action_type')
                            ->label('Action')
                            ->options([
                                'created' => 'Created',
                                'updated' => 'Updated',
                                'deleted' => 'Deleted',
                                'imported_csv' => 'Imported CSV',
                                'import_failed' => 'Import Failed',
                            ])
                            ->placeholder('All actions'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $action = $data['action_type'] ?? null;

                        if (! $action) {
                            return $query;
                        }

                        return $query->where('event', $action);
                    }),
                Tables\Filters\Filter::make('date_range')
                    ->label('Date Range')
                    ->form([
                        DatePicker::make('from_date')->label('From'),
                        DatePicker::make('to_date')->label('To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                filled($data['from_date'] ?? null),
                                fn (Builder $inner) => $inner->whereDate('created_at', '>=', $data['from_date'])
                            )
                            ->when(
                                filled($data['to_date'] ?? null),
                                fn (Builder $inner) => $inner->whereDate('created_at', '<=', $data['to_date'])
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Activity')
                ->schema([
                    TextEntry::make('causer.name')->label('Who')->placeholder('System'),
                    TextEntry::make('description')->label('What'),
                    TextEntry::make('created_at')->label('When')->dateTime('M d, Y H:i:s'),
                    TextEntry::make('subject_type')->label('Subject Type')->placeholder('-'),
                    TextEntry::make('subject_id')->label('Subject ID')->placeholder('-'),
                ])->columns(2),
            Section::make('Changes')
                ->schema([
                    KeyValueEntry::make('properties.old')->label('Before')->placeholder('No previous values'),
                    KeyValueEntry::make('properties.attributes')->label('After')->placeholder('No updated values'),
                ])->columns(2),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
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
