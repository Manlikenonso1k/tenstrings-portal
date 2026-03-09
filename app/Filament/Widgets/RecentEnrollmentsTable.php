<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentEnrollmentsTable extends BaseWidget
{
    protected static ?string $heading = 'Recent Enrollments';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Enrollment::query()->latest()->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('enrollment_number')->label('Enrollment ID'),
                Tables\Columns\TextColumn::make('student.student_number')->label('Student ID'),
                Tables\Columns\TextColumn::make('student.first_name')->label('Student'),
                Tables\Columns\TextColumn::make('enrollment_date')->date(),
                Tables\Columns\BadgeColumn::make('status'),
            ]);
    }
}
