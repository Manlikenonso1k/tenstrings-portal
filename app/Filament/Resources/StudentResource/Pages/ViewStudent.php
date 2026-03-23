<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Hash;

class ViewStudent extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    protected static string $view = 'filament.resources.student-resource.pages.view-student-hub';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reset_student_password')
                ->label('Reset Password')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->form([
                    \Filament\Forms\Components\TextInput::make('new_password')
                        ->label('New Password')
                        ->password()
                        ->revealable()
                        ->required()
                        ->minLength(8)
                        ->same('new_password_confirmation'),
                    \Filament\Forms\Components\TextInput::make('new_password_confirmation')
                        ->label('Confirm Password')
                        ->password()
                        ->revealable()
                        ->required()
                        ->minLength(8),
                ])
                ->action(function (array $data): void {
                    $user = $this->record->user;

                    if (! $user) {
                        Notification::make()
                            ->title('No linked user account')
                            ->body('This student does not have a linked login account yet.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $user->forceFill([
                        'password' => Hash::make((string) $data['new_password']),
                    ])->save();

                    Notification::make()
                        ->title('Password reset successful')
                        ->body('Student login password has been updated.')
                        ->success()
                        ->send();
                }),
            Actions\EditAction::make(),
        ];
    }
}
