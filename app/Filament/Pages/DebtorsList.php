<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DebtorsChartWidget;
use App\Models\Student;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DebtorsList extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'School Management';

    protected static ?string $navigationLabel = 'Branch Pricing for Debtors';

    protected static ?string $title = 'Debtors List';

    protected static string $view = 'filament.pages.debtors-list';

    protected function getHeaderWidgets(): array
    {
        return [
            DebtorsChartWidget::class,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Student::query()->where('balance_due', '>', 0))
            ->columns([
                ImageColumn::make('avatar_url')
                    ->label('Photo')
                    ->disk('public_uploads')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=Student&background=3b82f6&color=fff&size=64')
                    ->width(48)
                    ->height(48),
                TextColumn::make('student_number')->searchable()->sortable(),
                TextColumn::make('first_name')->searchable(),
                TextColumn::make('last_name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('phone'),
                TextColumn::make('branch')->badge(),
                TextColumn::make('address')->limit(40)->tooltip(fn ($record) => $record->address),
                TextColumn::make('guardian_phone')->label('Guardian Phone'),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'inactive',
                        'primary' => 'graduated',
                    ]),
                TextColumn::make('registration_date')->date(),
            ]);
    }
}
