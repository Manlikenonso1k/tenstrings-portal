<?php

namespace App\Filament\Portal\Pages;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

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

        $photoUrl = $student?->avatar_url
            ? asset('uploads/' . ltrim($student->avatar_url, '/'))
            : null;

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
            Forms\Components\Placeholder::make('current_passport_preview')
                ->label('Current Passport Photo')
                ->content(function () use ($photoUrl): HtmlString {
                    if ($photoUrl) {
                        return new HtmlString(
                            '<img src="' . e($photoUrl) . '" alt="Passport Photo"
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
