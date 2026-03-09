<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GradeResource\Pages;
use App\Models\Grade;
use App\Support\GradeCalculator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GradeResource extends Resource
{
    protected static ?string $model = Grade::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationGroup = 'Academic Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('student_id')->relationship('student', 'student_number')->searchable()->preload()->required(),
            Forms\Components\Select::make('course_id')->relationship('course', 'name')->searchable()->preload()->required(),
            Forms\Components\Select::make('instructor_id')->relationship('instructor', 'first_name')->searchable()->preload(),
            Forms\Components\Select::make('assessment_type')->options([
                'quiz' => 'Quiz',
                'exam' => 'Exam',
                'practical' => 'Practical',
                'final' => 'Final',
            ])->required(),
            Forms\Components\Select::make('assessment_month')->options([
                'FEBRUARY' => 'FEBRUARY',
                'MAY' => 'MAY',
                'AUGUST' => 'AUGUST',
                'NOVEMBER' => 'NOVEMBER',
            ])->required(),
            Forms\Components\TextInput::make('score')->numeric()->required()->live(),
            Forms\Components\TextInput::make('maximum_score')->numeric()->required()->default(100)->live(),
            Forms\Components\TextInput::make('percentage')->numeric()->readOnly(),
            Forms\Components\TextInput::make('grade_letter')->readOnly(),
            Forms\Components\DatePicker::make('date_recorded')->required()->default(now()),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.student_number')->label('Student ID')->searchable(),
                Tables\Columns\TextColumn::make('course.name')->searchable(),
                Tables\Columns\TextColumn::make('assessment_type'),
                Tables\Columns\TextColumn::make('assessment_month')->badge(),
                Tables\Columns\TextColumn::make('score'),
                Tables\Columns\TextColumn::make('maximum_score'),
                Tables\Columns\TextColumn::make('percentage'),
                Tables\Columns\BadgeColumn::make('grade_letter'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('assessment_month')->options([
                    'FEBRUARY' => 'FEBRUARY',
                    'MAY' => 'MAY',
                    'AUGUST' => 'AUGUST',
                    'NOVEMBER' => 'NOVEMBER',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGrades::route('/'),
            'create' => Pages\CreateGrade::route('/create'),
            'edit' => Pages\EditGrade::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'instructor', 'student'], true);
    }

    public static function mutateGradeData(array $data): array
    {
        $percentage = GradeCalculator::percentage((float) $data['score'], (float) $data['maximum_score']);
        $data['percentage'] = $percentage;
        $data['grade_letter'] = GradeCalculator::letter($percentage);

        return $data;
    }
}
