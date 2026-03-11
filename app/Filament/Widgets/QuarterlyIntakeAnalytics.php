<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class QuarterlyIntakeAnalytics extends BaseWidget
{
    protected static ?string $heading = 'Enrollment Peaks (Feb/May/Aug/Nov)';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Student::query()
                    ->mainIntakeMonths()
                    ->selectRaw('MIN(id) as id, YEAR(start_date) as intake_year, MONTH(start_date) as intake_month_number, DATE_FORMAT(start_date, "%M %Y") as intake_period, COUNT(*) as enrollments')
                    ->groupByRaw('YEAR(start_date), MONTH(start_date), DATE_FORMAT(start_date, "%M %Y")')
                    ->orderByRaw('YEAR(start_date) DESC, MONTH(start_date) DESC')
            )
            ->columns([
                Tables\Columns\TextColumn::make('intake_period')->label('Start Month/Year'),
                Tables\Columns\TextColumn::make('enrollments')->label('Enrollments')->sortable(),
            ])
            ->defaultSort('intake_year', 'desc');
    }
}
