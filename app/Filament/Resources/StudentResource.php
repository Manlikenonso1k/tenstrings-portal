<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'School Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Student Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('middle_name')->maxLength(255),
                        Forms\Components\TextInput::make('last_name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('phone')->tel()->required()->maxLength(30),
                        Forms\Components\Textarea::make('address')->columnSpanFull(),
                        Forms\Components\Select::make('branch')
                            ->options([
                                'AJAH BRANCH' => 'AJAH BRANCH',
                                'AGEGE BRANCH' => 'AGEGE BRANCH',
                                'IKEJA BRANCH' => 'IKEJA BRANCH',
                                'FESTAC BRANCH' => 'FESTAC BRANCH',
                            ])
                            ->required(),
                        Forms\Components\DatePicker::make('date_of_birth'),
                        Forms\Components\DatePicker::make('registration_date')->required()->default(now()),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'graduated' => 'Graduated',
                            ])
                            ->required(),
                        Forms\Components\FileUpload::make('photo_path')
                            ->image()
                            ->disk('public_uploads')
                            ->directory('students/photos')
                            ->maxSize(10240)
                            ->acceptedFileTypes(['image/jpeg', 'image/png']),
                    ])->columns(2),
                Forms\Components\Section::make('Guardian Information')
                    ->schema([
                        Forms\Components\TextInput::make('guardian_name')->maxLength(255),
                        Forms\Components\TextInput::make('guardian_phone')->tel()->maxLength(30),
                        Forms\Components\TextInput::make('guardian_email')->email()->maxLength(255),
                        Forms\Components\TextInput::make('guardian_relationship')->maxLength(255),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('first_name')->searchable(),
                Tables\Columns\TextColumn::make('last_name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('branch')->badge(),
                Tables\Columns\TextColumn::make('address')->limit(40)->tooltip(fn ($record) => $record->address),
                Tables\Columns\TextColumn::make('guardian_phone')->label('Guardian Phone'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'inactive',
                        'primary' => 'graduated',
                    ]),
                Tables\Columns\TextColumn::make('registration_date')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'graduated' => 'Graduated',
                ]),
                Tables\Filters\SelectFilter::make('branch')->options([
                    'AJAH BRANCH' => 'AJAH BRANCH',
                    'AGEGE BRANCH' => 'AGEGE BRANCH',
                    'IKEJA BRANCH' => 'IKEJA BRANCH',
                    'FESTAC BRANCH' => 'FESTAC BRANCH',
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user?->role === 'student' && $user->student) {
            return $query->where('id', $user->student->id);
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'admin';
    }

    public static function canAccess(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'admin'
            && (auth()->user()?->isAdmin() ?? false);
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }
}
