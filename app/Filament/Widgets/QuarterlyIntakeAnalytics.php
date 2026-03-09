<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class QuarterlyIntakeAnalytics extends BaseWidget
{
    protected static ?string $heading = 'Quarterly Intake (Feb/May/Aug/Nov)';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DB::table('enrollments')
                    ->selectRaw('intake_month, COUNT(*) as enrollments')
                    ->whereIn('intake_month', ['FEBRUARY', 'MAY', 'AUGUST', 'NOVEMBER'])
                    ->groupBy('intake_month')
            )
            ->columns([
                Tables\Columns\TextColumn::make('intake_month')->label('Month'),
                Tables\Columns\TextColumn::make('enrollments')->label('Enrollments')->sortable(),
            ])
            ->defaultSort('enrollments', 'desc');
    }
}
