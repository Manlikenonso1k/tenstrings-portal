<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use App\Models\Grade;
use App\Support\CourseCatalog;
use App\Support\StudentMatricMailer;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'School Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(4)->schema([
                Forms\Components\Section::make('IDENTITY & PASSPORT')
                    ->schema([
                        Forms\Components\FileUpload::make('avatar_url')
                            ->label('Update Passport')
                            ->image()
                            ->disk('public_uploads')
                            ->directory('students/passport')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg']),
                        Forms\Components\TextInput::make('student_number')->disabled(),
                    ]),
                Forms\Components\Section::make('CORE INFORMATION')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('middle_name')->maxLength(255),
                        Forms\Components\TextInput::make('last_name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('phone')->tel()->required()->maxLength(30),
                        Forms\Components\TextInput::make('guardian_phone')->tel()->maxLength(30),
                        Forms\Components\Textarea::make('address'),
                        Forms\Components\Select::make('sex')
                            ->options([
                                'Male' => 'Male',
                                'Female' => 'Female',
                            ])
                            ->native(false),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->maxDate(now()->subYears(5)),
                        Forms\Components\Select::make('branch')
                            ->options([
                                'AJAH BRANCH' => 'AJAH BRANCH',
                                'AGEGE BRANCH' => 'AGEGE BRANCH',
                                'IKEJA BRANCH' => 'IKEJA BRANCH',
                                'FESTAC BRANCH' => 'FESTAC BRANCH',
                            ])
                            ->required(),
                        Forms\Components\Select::make('selected_course_name')
                            ->label('Course Selection')
                            ->options(CourseCatalog::courseOptions())
                            ->searchable()
                            ->live()
                            ->required()
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                $set('selected_course_code', CourseCatalog::codeFor((string) $state));

                                $defaultDuration = CourseCatalog::defaultDurationFor($state);
                                if ($defaultDuration) {
                                    $set('duration', $defaultDuration);
                                } else {
                                    $set('duration', null);
                                }
                            }),
                        Forms\Components\Select::make('duration')
                            ->label('Course Duration')
                            ->options(fn (Get $get): array => CourseCatalog::durationOptionsFor($get('selected_course_name')))
                            ->required()
                            ->disabled(fn (Get $get): bool => CourseCatalog::hasSingleDuration($get('selected_course_name')))
                            ->dehydrated(),
                        Forms\Components\DatePicker::make('start_date')
                            ->required()
                            ->default(now()->toDateString()),
                        Forms\Components\Hidden::make('selected_course_code')
                            ->dehydrateStateUsing(fn ($state, Get $get): string => CourseCatalog::codeFor((string) $get('selected_course_name'))),
                    ]),
                Forms\Components\Section::make('ACADEMIC & PROGRESS')
                    ->schema([
                        Forms\Components\Placeholder::make('feb_stats')
                            ->label('February')
                            ->content(fn (?Student $record): string => self::quarterlyStat($record, 'FEBRUARY')),
                        Forms\Components\Placeholder::make('may_stats')
                            ->label('May')
                            ->content(fn (?Student $record): string => self::quarterlyStat($record, 'MAY')),
                        Forms\Components\Placeholder::make('aug_stats')
                            ->label('August')
                            ->content(fn (?Student $record): string => self::quarterlyStat($record, 'AUGUST')),
                        Forms\Components\Placeholder::make('nov_stats')
                            ->label('November')
                            ->content(fn (?Student $record): string => self::quarterlyStat($record, 'NOVEMBER')),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('print_admission_letter')
                                ->label('Print Admission Letter')
                                ->url(fn (?Student $record) => $record ? route('students.print.admission_letter', $record) : null)
                                ->openUrlInNewTab(),
                            Forms\Components\Actions\Action::make('print_biodata')
                                ->label('Print Bio-data')
                                ->url(fn (?Student $record) => $record ? route('students.print.biodata', $record) : null)
                                ->openUrlInNewTab(),
                        ]),
                    ]),
                Forms\Components\Section::make('DOCUMENT VAULT')
                    ->schema([
                        Forms\Components\FileUpload::make('birth_certificate_path')
                            ->label('Birth Certificate')
                            ->disk('public_uploads')
                            ->directory('students/documents')
                            ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'])
                            ->maxSize(2048),
                        Forms\Components\FileUpload::make('jamb_path')
                            ->label('JAMB')
                            ->disk('public_uploads')
                            ->directory('students/documents')
                            ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'])
                            ->maxSize(2048),
                        Forms\Components\FileUpload::make('neco_path')
                            ->label('NECO')
                            ->disk('public_uploads')
                            ->directory('students/documents')
                            ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'])
                            ->maxSize(2048),
                        Forms\Components\FileUpload::make('waec_path')
                            ->label('WAEC')
                            ->disk('public_uploads')
                            ->directory('students/documents')
                            ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'])
                            ->maxSize(2048),
                    ]),
            ]),
        ]);
    }

    private static function quarterlyStat(?Student $record, string $month): string
    {
        if (! $record) {
            return '-';
        }

        $query = Grade::query()->where('student_id', $record->id)->where('assessment_month', $month);
        $count = $query->count();

        if ($count === 0) {
            return 'No assessment yet';
        }

        $avg = round((float) $query->avg('percentage'), 2);

        return $count . ' assessments | Avg: ' . $avg . '%';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('Photo')
                    ->disk('public_uploads')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=Student&background=3b82f6&color=fff&size=64')
                    ->width(48)
                    ->height(48),
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
                Tables\Actions\Action::make('resend_matric_email')
                    ->label('Resend Matric')
                    ->icon('heroicon-o-envelope')
                    ->visible(fn () => in_array(Auth::user()?->role, ['super_admin', 'admin'], true))
                    ->action(function (Student $record): void {
                        try {
                            StudentMatricMailer::send($record);

                            Notification::make()
                                ->title('Matric email sent')
                                ->body('Matric number has been emailed to ' . $record->email)
                                ->success()
                                ->send();
                        } catch (Throwable $exception) {
                            Log::warning('Failed to resend student matric email.', [
                                'student_id' => $record->id,
                                'email' => $record->email,
                                'error' => $exception->getMessage(),
                            ]);

                            Notification::make()
                                ->title('Email failed')
                                ->body('Unable to send matric email right now.')
                                ->danger()
                                ->send();
                        }
                    }),
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
            'identity' => Pages\EditStudentIdentity::route('/{record}/identity'),
            'core' => Pages\EditStudentCore::route('/{record}/core-information'),
            'academic' => Pages\ViewStudentAcademic::route('/{record}/academic-progress'),
            'documents' => Pages\EditStudentDocuments::route('/{record}/documents'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user?->role === 'student' && $user->student) {
            return $query->where('id', $user->student->id);
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        return in_array(Auth::user()?->role, ['super_admin', 'admin'], true);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'admin';
    }

    public static function canAccess(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'admin'
            && in_array(Auth::user()?->role, ['super_admin', 'admin'], true);
    }

    public static function canEdit($record): bool
    {
        return in_array(Auth::user()?->role, ['super_admin', 'admin'], true);
    }

    public static function canDelete($record): bool
    {
        return in_array(Auth::user()?->role, ['super_admin', 'admin'], true);
    }
}
