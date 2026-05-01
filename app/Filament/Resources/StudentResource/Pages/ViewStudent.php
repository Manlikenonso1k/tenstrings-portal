<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use App\Services\Payments\PaymentService;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class ViewStudent extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    protected static string $view = 'filament.resources.student-resource.pages.view-student-hub';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download_invoice')
                ->label('Download Invoice')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(function (): ?string {
                    $invoiceId = Invoice::query()
                        ->where('student_id', $this->record->id)
                        ->latest('id')
                        ->value('id');

                    if (! $invoiceId) {
                        return null;
                    }

                    return URL::temporarySignedRoute(
                        'documents.invoices.download',
                        now()->addMinutes(15),
                        ['invoice' => $invoiceId]
                    );
                })
                ->visible(fn (): bool => Invoice::query()->where('student_id', $this->record->id)->exists())
                ->openUrlInNewTab(),
            Actions\Action::make('download_receipt')
                ->label('Download Receipt')
                ->icon('heroicon-o-document-arrow-down')
                ->url(function (): ?string {
                    $paymentId = Payment::query()
                        ->where('student_id', $this->record->id)
                        ->where('status', 'success')
                        ->latest('id')
                        ->value('id');

                    if (! $paymentId) {
                        return null;
                    }

                    return URL::temporarySignedRoute(
                        'documents.receipts.download',
                        now()->addMinutes(15),
                        ['payment' => $paymentId]
                    );
                })
                ->visible(fn (): bool => Payment::query()
                    ->where('student_id', $this->record->id)
                    ->where('status', 'success')
                    ->exists())
                ->openUrlInNewTab(),
            Actions\Action::make('generate_future_quarter_invoice')
                ->label('Generate Future Quarter Invoice')
                ->icon('heroicon-o-calendar-days')
                ->form([
                    TextInput::make('amount')
                        ->label('Amount (NGN)')
                        ->prefix('₦')
                        ->numeric()
                        ->required()
                        ->minValue(1),
                    Select::make('future_offset')
                        ->label('Quarter')
                        ->options([
                            1 => 'Next Quarter',
                            2 => '2 Quarters Ahead',
                            3 => '3 Quarters Ahead',
                        ])
                        ->default(1)
                        ->required(),
                ])
                ->action(function (array $data): void {
                    /** @var PaymentService $paymentService */
                    $paymentService = app(PaymentService::class);

                    $invoice = $paymentService->createFutureQuarterInvoice(
                        (int) $this->record->id,
                        (float) $data['amount'],
                        (int) $data['future_offset']
                    );

                    Notification::make()
                        ->title('Future quarter invoice created')
                        ->body('Invoice ' . $invoice->quarter_name . ' generated successfully.')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('reset_student_password')
                ->label('Reset Password')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->form([
                    TextInput::make('new_password')
                        ->label('New Password')
                        ->password()
                        ->revealable()
                        ->required()
                        ->minLength(8)
                        ->same('new_password_confirmation'),
                    TextInput::make('new_password_confirmation')
                        ->label('Confirm Password')
                        ->password()
                        ->revealable()
                        ->required()
                        ->minLength(8),
                ])
                ->action(function (array $data): void {
                    $user = $this->record->user;

                    if (! $user) {
                        $email = strtolower(trim((string) $this->record->email));

                        if ($email !== '') {
                            $user = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();
                        }

                        if (! $user) {
                            $user = User::query()->create([
                                'name' => trim(($this->record->first_name ?? '') . ' ' . ($this->record->last_name ?? '')),
                                'email' => $email,
                                'phone' => (string) ($this->record->phone ?? 'N/A'),
                                'role' => 'student',
                                'password' => Hash::make((string) $data['new_password']),
                            ]);
                        }

                        $this->record->forceFill([
                            'user_id' => $user->id,
                        ])->save();
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
