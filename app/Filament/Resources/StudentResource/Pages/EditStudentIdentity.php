<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Support\CourseCatalog;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;

class EditStudentIdentity extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected static string $view = 'filament.resources.student-resource.pages.edit-student-identity';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Placeholder::make('current_passport_preview')
                ->label('Current Passport Photo')
                ->content(function (): HtmlString {
                    $path = $this->record?->avatar_url;
                    if ($path) {
                        $url = asset('uploads/' . ltrim($path, '/'));

                        return new HtmlString(
                            '<img src="' . e($url) . '" alt="Passport Photo"
                                  style="width:160px;height:160px;object-fit:cover;border-radius:50%;border:3px solid #3b82f6;box-shadow:0 2px 8px rgba(0,0,0,0.15);">'
                        );
                    }

                    return new HtmlString(
                        '<div style="width:160px;height:160px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;border:3px solid #d1d5db;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="#9ca3af" style="width:80px;height:80px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                            </svg>
                        </div>'
                    );
                }),
            Forms\Components\FileUpload::make('avatar_url')
                ->label('Upload New Passport Photo')
                ->image()
                ->disk('public_uploads')
                ->directory('students/passport')
                ->imagePreviewHeight('200')
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
