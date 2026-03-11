<?php

namespace App\Filament\Portal\Pages;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class StudentIdentityPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.portal.pages.student-identity-page';

    public ?array $data = [];

    public function mount(): void
    {
        $student = Auth::user()?->student;

        $this->form->fill($student?->only(['avatar_url']) ?? []);
    }

    public function form(Form $form): Form
    {
        $student = Auth::user()?->student;

        return $form->statePath('data')->schema([
            Forms\Components\Placeholder::make('student_number')
                ->label('Matric Number')
                ->content($student?->student_number ?? '-'),
            Forms\Components\Placeholder::make('full_name')
                ->label('Full Name')
                ->content(trim(($student?->first_name ?? '') . ' ' . ($student?->last_name ?? ''))),
            Forms\Components\Placeholder::make('selected_course')
                ->label('Enrolled Course')
                ->content($student?->selected_course_name ?? '-'),
            Forms\Components\Placeholder::make('duration')
                ->label('Duration')
                ->content($student?->duration ?? '-'),
            Forms\Components\FileUpload::make('avatar_url')
                ->label('Passport Photo')
                ->image()
                ->disk('public_uploads')
                ->directory('students/passport')
                ->maxSize(2048)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg']),
        ]);
    }

    public function save(): void
    {
        $student = Auth::user()?->student;

        if (! $student) {
            return;
        }

        $student->update($this->form->getState());

        Notification::make()->title('Passport updated successfully')->success()->send();
    }
}
