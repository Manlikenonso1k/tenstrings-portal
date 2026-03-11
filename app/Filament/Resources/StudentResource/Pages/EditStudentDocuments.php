<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditStudentDocuments extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected static string $view = 'filament.resources.student-resource.pages.edit-student-documents';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('birth_certificate_path')
                ->label('Birth Certificate')
                ->disk('public_uploads')
                ->directory('students/documents')
                ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'])
                ->maxSize(2048),
            Forms\Components\FileUpload::make('jamb_path')
                ->label('JAMB Result')
                ->disk('public_uploads')
                ->directory('students/documents')
                ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'])
                ->maxSize(2048),
            Forms\Components\FileUpload::make('neco_path')
                ->label('NECO Result')
                ->disk('public_uploads')
                ->directory('students/documents')
                ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'])
                ->maxSize(2048),
            Forms\Components\FileUpload::make('waec_path')
                ->label('WAEC Result')
                ->disk('public_uploads')
                ->directory('students/documents')
                ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'])
                ->maxSize(2048),
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
