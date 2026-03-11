<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Support\CourseCatalog;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditStudentIdentity extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected static string $view = 'filament.resources.student-resource.pages.edit-student-identity';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('avatar_url')
                ->label('Passport Photo')
                ->image()
                ->disk('public_uploads')
                ->directory('students/passport')
                ->maxSize(2048)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg']),
            Forms\Components\TextInput::make('student_number')
                ->label('Matric Number')
                ->disabled(),
            Forms\Components\Select::make('selected_course_name')
                ->label('Course Selection')
                ->options(CourseCatalog::courseOptions())
                ->searchable()
                ->live()
                ->afterStateUpdated(function (Forms\Set $set, ?string $state): void {
                    $set('selected_course_code', CourseCatalog::codeFor((string) $state));
                    $default = CourseCatalog::defaultDurationFor($state);
                    if ($default) {
                        $set('duration', $default);
                    }
                }),
            Forms\Components\Select::make('duration')
                ->label('Course Duration')
                ->options(fn (Forms\Get $get): array => CourseCatalog::durationOptionsFor($get('selected_course_name')))
                ->disabled(fn (Forms\Get $get): bool => CourseCatalog::hasSingleDuration($get('selected_course_name')))
                ->dehydrated(),
            Forms\Components\DatePicker::make('start_date')
                ->label('Start Date'),
            Forms\Components\Hidden::make('selected_course_code')
                ->dehydrateStateUsing(fn ($state, Forms\Get $get): string => CourseCatalog::codeFor((string) $get('selected_course_name'))),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back_to_hub')
                ->label('Back to Hub')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => ViewStudent::getUrl(['record' => $this->record->getRouteKey()])),
        ];
    }
}
