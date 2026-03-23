<?php

namespace App\Filament\Portal\Pages;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentPasswordPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.portal.pages.student-password-page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\TextInput::make('current_password')
                    ->label('Current Password')
                    ->password()
                    ->revealable()
                    ->required(),
                Forms\Components\TextInput::make('new_password')
                    ->label('New Password')
                    ->password()
                    ->revealable()
                    ->required()
                    ->minLength(8)
                    ->same('new_password_confirmation'),
                Forms\Components\TextInput::make('new_password_confirmation')
                    ->label('Confirm New Password')
                    ->password()
                    ->revealable()
                    ->required()
                    ->minLength(8),
            ]);
    }

    public function save(): void
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return;
        }

        $state = $this->form->getState();

        if (! Hash::check((string) ($state['current_password'] ?? ''), (string) $user->password)) {
            Notification::make()
                ->title('Password not updated')
                ->body('Current password is incorrect.')
                ->danger()
                ->send();

            return;
        }

        $user->update([
            'password' => Hash::make((string) $state['new_password']),
        ]);

        $this->form->fill([
            'current_password' => null,
            'new_password' => null,
            'new_password_confirmation' => null,
        ]);

        Notification::make()
            ->title('Password updated')
            ->body('Your portal password has been changed successfully.')
            ->success()
            ->send();
    }
}
