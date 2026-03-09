<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnrollmentResource\Pages;
use App\Models\Enrollment;
use App\Support\EnrollmentLimitService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationGroup = 'School Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('student_id')
                ->relationship('student', 'student_number')
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->student_number . ' - ' . $record->first_name . ' ' . $record->last_name)
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\Select::make('courses')
                ->relationship('courses', 'name')
                ->multiple()
                ->preload()
                ->required()
                ->maxItems(2)
                ->helperText('A student can only be enrolled in maximum 2 ongoing courses.'),
            Forms\Components\DatePicker::make('enrollment_date')->required()->default(now()),
            Forms\Components\Select::make('intake_month')
                ->options([
                    'FEBRUARY' => 'FEBRUARY',
                    'MAY' => 'MAY',
                    'AUGUST' => 'AUGUST',
                    'NOVEMBER' => 'NOVEMBER',
                ])
                ->required(),
            Forms\Components\DatePicker::make('start_date')->required(),
            Forms\Components\DatePicker::make('expected_end_date')
                ->required()
                ->after('start_date'),
            Forms\Components\Select::make('status')
                ->options([
                    'ongoing' => 'Ongoing',
                    'completed' => 'Completed',
                    'dropped' => 'Dropped',
                ])
                ->required(),
            Forms\Components\Textarea::make('notes')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('enrollment_number')->searchable(),
                Tables\Columns\TextColumn::make('student.student_number')->label('Student ID')->searchable(),
                Tables\Columns\TextColumn::make('student.first_name')->label('Student')->searchable(),
                Tables\Columns\TextColumn::make('intake_month')->badge(),
                Tables\Columns\TextColumn::make('courses.name')->badge()->separator(', '),
                Tables\Columns\TextColumn::make('start_date')->date(),
                Tables\Columns\TextColumn::make('expected_end_date')->date(),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'warning' => 'ongoing',
                    'success' => 'completed',
                    'danger' => 'dropped',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('intake_month')->options([
                    'FEBRUARY' => 'FEBRUARY',
                    'MAY' => 'MAY',
                    'AUGUST' => 'AUGUST',
                    'NOVEMBER' => 'NOVEMBER',
                ]),
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
            'index' => Pages\ListEnrollments::route('/'),
            'create' => Pages\CreateEnrollment::route('/create'),
            'edit' => Pages\EditEnrollment::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'instructor', 'student'], true);
    }

    public static function canCreate(): bool
    {
        return in_array(auth()->user()?->role, ['admin'], true);
    }

    public static function validateMaxTwoCourses(int $studentId, array $courseIds, ?int $ignoreEnrollmentId = null): void
    {
        if (! EnrollmentLimitService::canEnrollInCourses($studentId, $courseIds, $ignoreEnrollmentId)) {
            Notification::make()
                ->title('Enrollment limit reached')
                ->body('This student cannot be enrolled in more than 2 ongoing courses.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'courses' => 'A student can only be enrolled in maximum 2 ongoing courses at a time.',
            ]);
        }
    }
}
