<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class QuarterlyIntakeAnalytics extends BaseWidget
{
    protected static ?string $heading = 'Enrollment Peaks (Feb/May/Aug/Nov)';

    public function table(Table $table): Table
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $query = Student::query()
                ->mainIntakeMonths()
                ->selectRaw("MIN(id) as id, CAST(strftime('%Y', start_date) AS INTEGER) as intake_year, CAST(strftime('%m', start_date) AS INTEGER) as intake_month_number, strftime('%Y-%m', start_date) as intake_period, COUNT(*) as enrollments")
                ->groupByRaw("strftime('%Y', start_date), strftime('%m', start_date)")
                ->orderByRaw("strftime('%Y', start_date) DESC, strftime('%m', start_date) DESC");
        } else {
            $query = Student::query()
                ->mainIntakeMonths()
                ->selectRaw('MIN(id) as id, YEAR(start_date) as intake_year, MONTH(start_date) as intake_month_number, DATE_FORMAT(start_date, "%M %Y") as intake_period, COUNT(*) as enrollments')
                ->groupByRaw('YEAR(start_date), MONTH(start_date), DATE_FORMAT(start_date, "%M %Y")')
                ->orderByRaw('YEAR(start_date) DESC, MONTH(start_date) DESC');
        }

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('intake_period')
                    ->label('Start Month/Year')
                    ->formatStateUsing(function (string $state) use ($driver): string {
                        if ($driver === 'sqlite' && preg_match('/^(\d{4})-(\d{2})$/', $state, $parts)) {
                            $names = ['02' => 'February', '05' => 'May', '08' => 'August', '11' => 'November'];
                            $name = $names[$parts[2]] ?? date('F', mktime(0, 0, 0, (int) $parts[2], 1));
                            return $name . ' ' . $parts[1];
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('enrollments')->label('Enrollments')->sortable(),
            ]);
    }
}
