<?php

namespace App\Filament\Portal\Pages;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class StudentDocumentsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.portal.pages.student-documents-page';

    public ?array $data = [];

    public function mount(): void
    {
        $student = Auth::user()?->student;

        $this->form->fill($student?->only([
            'birth_certificate_path',
            'jamb_path',
            'neco_path',
            'waec_path',
        ]) ?? []);
    }

    public function form(Form $form): Form
    {
        return $form->statePath('data')->schema([
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

    public function save(): void
    {
        $student = Auth::user()?->student;

        if (! $student) {
            return;
        }

        $student->update($this->form->getState());

        Notification::make()->title('Documents uploaded successfully')->success()->send();
    }
}
