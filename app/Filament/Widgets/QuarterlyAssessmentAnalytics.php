<?php

namespace App\Filament\Widgets;

use App\Models\Grade;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class QuarterlyAssessmentAnalytics extends BaseWidget
{
    protected static ?string $heading = 'Quarterly Assessment Performance';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Grade::query()
                    ->selectRaw('MIN(id) as id, assessment_month, COUNT(*) as assessments, ROUND(AVG(percentage), 2) as avg_percentage')
                    ->whereIn('assessment_month', ['FEBRUARY', 'MAY', 'AUGUST', 'NOVEMBER'])
                    ->groupBy('assessment_month')
            )
            ->columns([
                Tables\Columns\TextColumn::make('assessment_month')->label('Month'),
                Tables\Columns\TextColumn::make('assessments')->label('Assessments')->sortable(),
                Tables\Columns\TextColumn::make('avg_percentage')->label('Average %')->sortable(),
            ])
            ->defaultSort('avg_percentage', 'desc');
    }
}
