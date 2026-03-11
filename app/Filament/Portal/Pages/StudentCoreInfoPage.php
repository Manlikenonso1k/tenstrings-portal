<?php

namespace App\Filament\Portal\Pages;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class StudentCoreInfoPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.portal.pages.student-core-info-page';

    public ?array $data = [];

    public function mount(): void
    {
        $student = Auth::user()?->student;

        $this->form->fill($student?->only(['address', 'phone', 'guardian_phone']) ?? []);
    }

    public function form(Form $form): Form
    {
        $student = Auth::user()?->student;

        return $form->statePath('data')->schema([
            Forms\Components\Placeholder::make('full_name')
                ->label('Full Name')
                ->content(trim(($student?->first_name ?? '') . ' ' . ($student?->middle_name ? $student->middle_name . ' ' : '') . ($student?->last_name ?? ''))),
            Forms\Components\Placeholder::make('email')
                ->label('Email Address')
                ->content($student?->email ?? '-'),
            Forms\Components\Placeholder::make('branch')
                ->label('Branch')
                ->content($student?->branch ?? '-'),
            Forms\Components\TextInput::make('phone')
                ->label('Phone Number')
                ->tel()
                ->maxLength(30),
            Forms\Components\TextInput::make('guardian_phone')
                ->label('Guardian Phone Number')
                ->tel()
                ->maxLength(30),
            Forms\Components\Textarea::make('address')
                ->label('Home Address')
                ->rows(3),
        ]);
    }

    public function save(): void
    {
        $student = Auth::user()?->student;

        if (! $student) {
            return;
        }

        $student->update($this->form->getState());

        Notification::make()->title('Core information updated')->success()->send();
    }
}
