<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BranchEnrollmentAnalytics extends BaseWidget
{
    protected static ?string $heading = 'Branch Enrollment Analytics';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Student::query()
                    ->leftJoin('enrollments', function ($join) {
                        $join->on('students.id', '=', 'enrollments.student_id')
                            ->where('enrollments.status', '=', 'ongoing');
                    })
                    ->selectRaw('MIN(students.id) as id, students.branch as branch, COUNT(DISTINCT students.id) as total_students, COUNT(DISTINCT enrollments.id) as ongoing_enrollments')
                    ->groupBy('students.branch')
            )
            ->columns([
                Tables\Columns\TextColumn::make('branch')->label('Branch'),
                Tables\Columns\TextColumn::make('total_students')->label('Students')->sortable(),
                Tables\Columns\TextColumn::make('ongoing_enrollments')->label('Ongoing Enrollments')->sortable(),
            ])
            ->defaultSort('total_students', 'desc');
    }
}
