<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Academic Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('student_id')->relationship('student', 'student_number')->searchable()->preload()->required(),
            Forms\Components\Select::make('course_id')->relationship('course', 'name')->searchable()->preload()->required(),
            Forms\Components\Select::make('instructor_id')->relationship('instructor', 'first_name')->searchable()->preload(),
            Forms\Components\DatePicker::make('attendance_date')->required()->default(now()),
            Forms\Components\Select::make('status')->options([
                'present' => 'Present',
                'absent' => 'Absent',
                'late' => 'Late',
                'excused' => 'Excused',
            ])->required(),
            Forms\Components\Textarea::make('instructor_notes')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('attendance_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('student.student_number')->label('Student ID')->searchable(),
                Tables\Columns\TextColumn::make('course.name')->searchable(),
                Tables\Columns\BadgeColumn::make('status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['super_admin', 'admin', 'instructor', 'student'], true);
    }
}
