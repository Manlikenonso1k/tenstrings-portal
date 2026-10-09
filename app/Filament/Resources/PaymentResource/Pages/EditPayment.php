<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Services\Payments\PaymentEvidenceApprovalService;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Throwable;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $amountChanged = (float) $data['amount_paid'] !== (float) $this->record->amount_paid;
        $studentChanged = (int) $data['student_id'] !== (int) $this->record->student_id;
        $courseChanged = (int) ($data['course_id'] ?? 0) !== (int) ($this->record->course_id ?? 0);

        if ($amountChanged || $studentChanged || $courseChanged) {
            PaymentResource::validatePaymentDoesNotExceedBalance($data);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('request_evidence_replacement')
                ->label('Request Evidence Replacement')
                ->icon('heroicon-o-shield-check')
                ->color('warning')
                ->visible(fn (): bool => in_array(Auth::user()?->role, ['super_admin', 'admin', 'accounts_clerk'], true))
                ->form([
                    FileUpload::make('replacement_path')
                        ->label('Replacement Receipt Evidence (PDF)')
                        ->disk('public_uploads')
                        ->directory('payments/evidence')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(5120)
                        ->required(),
                ])
                ->requiresConfirmation()
                ->modalHeading('Request developer approval')
                ->modalDescription('A one-time approval code will be emailed to the developer. The current receipt remains unchanged until the code is verified.')
                ->modalSubmitActionLabel('Send approval code')
                ->action(function (array $data): void {
                    try {
                        app(PaymentEvidenceApprovalService::class)->request(
                            $this->record,
                            Auth::user(),
                            $data['replacement_path'],
                        );

                        Notification::make()
                            ->title('Approval code sent')
                            ->body('Ask the developer for the approval code, then use Verify and Replace Evidence.')
                            ->success()
                            ->send();
                    } catch (Throwable $exception) {
                        report($exception);

                        Notification::make()
                            ->title('Approval request failed')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Actions\Action::make('verify_and_replace_evidence')
                ->label('Verify & Replace Evidence')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->visible(fn (): bool => in_array(Auth::user()?->role, ['super_admin', 'admin', 'accounts_clerk'], true))
                ->form([
                    TextInput::make('approval_code')
                        ->label('Developer approval code')
                        ->numeric()
                        ->length(6)
                        ->required(),
                ])
                ->modalHeading('Verify developer approval')
                ->modalDescription('Entering a valid code replaces only the receipt PDF; the payment amount and balance are not changed.')
                ->modalSubmitActionLabel('Replace evidence')
                ->action(function (array $data): void {
                    try {
                        app(PaymentEvidenceApprovalService::class)->replace(
                            $this->record,
                            Auth::user(),
                            $data['approval_code'],
                        );

                        Notification::make()
                            ->title('Receipt evidence replaced')
                            ->body('The payment amount and balance were not changed.')
                            ->success()
                            ->send();
                    } catch (Throwable $exception) {
                        Notification::make()
                            ->title('Evidence was not replaced')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Actions\DeleteAction::make(),
        ];
    }
}