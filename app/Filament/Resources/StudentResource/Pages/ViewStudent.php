<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\Course;
use App\Models\Invoice;
use App\Models\HostelPayment;
use App\Models\Payment;
use App\Models\StudentCourseFee;
use App\Models\User;
use App\Services\Payments\PaymentService;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Throwable;

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

            // ── Record Manual Payment (Admin only) ───────────────────────
            Actions\Action::make('record_payment')
                ->label('Record Payment')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->visible(fn (): bool => in_array(Auth::user()?->role, ['super_admin', 'admin', 'accounts_clerk'], true))
                ->authorize('create', Payment::class)
                ->form([
                    Select::make('course_id')
                        ->label('Course')
                        ->options(function (): array {
                            // Show courses from the student's course-fee records first,
                            // then fall back to the student's selected course.
                            $fees = StudentCourseFee::query()
                                ->where('student_id', $this->record->id)
                                ->with('course')
                                ->get();

                            if ($fees->isNotEmpty()) {
                                return $fees
                                    ->mapWithKeys(fn (StudentCourseFee $f) => [
                                        $f->course_id => ($f->course->name ?? 'Course #' . $f->course_id)
                                            . ' (Owes: ₦' . number_format((float) $f->outstanding_balance, 2) . ')',
                                    ])
                                    ->toArray();
                            }

                            // Fallback: resolve the student's selected course from the courses table
                            $course = Course::query()
                                ->where('name', $this->record->selected_course_name)
                                ->first();

                            if ($course) {
                                return [$course->id => $course->name];
                            }

                            return [];
                        })
                        ->required()
                        ->searchable(),

                    TextInput::make('amount_paid')
                        ->label('Amount Paid (NGN)')
                        ->prefix('₦')
                        ->numeric()
                        ->required()
                        ->minValue(1),

                    FileUpload::make('receipt_evidence_path')
                        ->label('Receipt Evidence (PDF)')
                        ->disk('public_uploads')
                        ->directory('payments/evidence')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(5120) // 5 MB
                        ->required(),

                    Textarea::make('notes')
                        ->label('Notes (optional)')
                        ->maxLength(500),
                ])
                ->requiresConfirmation()
                ->modalHeading('Record Manual Payment')
                ->modalDescription('Please upload the PDF receipt and enter the exact amount paid. This action will update the student\'s fee balance.')
                ->modalSubmitActionLabel('Record Payment')
                ->action(function (array $data): void {
                    try {
                        DB::transaction(function () use ($data): void {
                            $payment = Payment::query()->create([
                                'student_id' => $this->record->id,
                                'course_id' => (int) $data['course_id'],
                                'user_id' => Auth::id(),
                                'gateway' => 'manual',
                                'reference' => 'MANUAL-' . strtoupper(bin2hex(random_bytes(6))),
                                'amount' => (float) $data['amount_paid'],
                                'amount_paid' => (float) $data['amount_paid'],
                                'status' => 'success',
                                'payment_status' => 'paid',
                                'payment_method' => 'transfer',
                                'payment_date' => now(),
                                'processed_at' => now(),
                                'receipt_number' => 'REC-' . strtoupper(bin2hex(random_bytes(4))),
                                'receipt_evidence_path' => $data['receipt_evidence_path'] ?? null,
                                'notes' => $data['notes'] ?? null,
                            ]);

                            // The Payment model's `created` event already syncs StudentCourseFee.
                            // Now refresh the student-level financial snapshot.
                            $totals = StudentCourseFee::query()
                                ->where('student_id', $this->record->id)
                                ->selectRaw('COALESCE(SUM(total_course_fee), 0) as total_fee, COALESCE(SUM(amount_paid), 0) as paid, COALESCE(SUM(outstanding_balance), 0) as outstanding')
                                ->first();

                            $this->record->update([
                                'total_balance' => (float) ($totals->total_fee ?? 0),
                                'fees_paid' => (float) ($totals->paid ?? 0),
                                'balance_due' => (float) ($totals->outstanding ?? 0),
                            ]);

                            activity()
                                ->causedBy(Auth::user())
                                ->performedOn($this->record)
                                ->withProperties([
                                    'payment_id' => $payment->id,
                                    'amount' => (float) $data['amount_paid'],
                                    'course_id' => (int) $data['course_id'],
                                    'receipt_evidence' => $data['receipt_evidence_path'] ?? null,
                                ])
                                ->log('manual_payment_recorded');
                        });

                        Notification::make()
                            ->title('Payment recorded')
                            ->body('₦' . number_format((float) $data['amount_paid'], 2) . ' has been recorded and the student balance updated.')
                            ->success()
                            ->send();

                    } catch (Throwable $e) {
                        Log::error('Manual payment recording failed.', [
                            'student_id' => $this->record->id,
                            'error' => $e->getMessage(),
                        ]);

                        Notification::make()
                            ->title('Payment recording failed')
                            ->body('Could not record the payment. Please try again.')
                            ->danger()
                            ->send();
                    }
                }),

            Actions\Action::make('record_hostel_payment')
                ->label('Record Hostel Payment')
                ->icon('heroicon-o-home-modern')
                ->color('success')
                ->visible(fn (): bool => in_array(Auth::user()?->role, ['super_admin', 'admin', 'accounts_clerk'], true))
                ->authorize('create', HostelPayment::class)
                ->form([
                    TextInput::make('amount')
                        ->label('Amount Paid (NGN)')
                        ->prefix('₦')
                        ->numeric()
                        ->required()
                        ->minValue(1),
                    Select::make('payment_method')
                        ->label('Payment Method')
                        ->options([
                            'cash' => 'Cash',
                            'card' => 'Card',
                            'transfer' => 'Transfer',
                            'cheque' => 'Cheque',
                        ])
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('payment_date')
                        ->label('Payment Date')
                        ->default(now())
                        ->required(),
                    FileUpload::make('receipt_evidence_path')
                        ->label('Receipt Evidence (optional)')
                        ->disk('public_uploads')
                        ->directory('hostel-payments/evidence')
                        ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'])
                        ->maxSize(5120),
                    Textarea::make('notes')
                        ->label('Notes (optional)')
                        ->maxLength(500),
                ])
                ->requiresConfirmation()
                ->modalHeading('Record Hostel Payment')
                ->modalDescription('The payment is added to the student hostel ledger and cannot exceed the outstanding hostel balance.')
                ->modalSubmitActionLabel('Record Hostel Payment')
                ->action(function (array $data): void {
                    try {
                        DB::transaction(function () use ($data): void {
                            $student = $this->record->newQuery()
                                ->lockForUpdate()
                                ->findOrFail($this->record->getKey());

                            $paid = (float) $student->hostelPayments()
                                ->where('status', 'paid')
                                ->sum('amount');
                            $outstanding = max(0, (float) $student->hostel_fee - $paid);
                            $amount = (float) $data['amount'];

                            if ($outstanding <= 0) {
                                throw ValidationException::withMessages([
                                    'amount' => 'This student has no outstanding hostel balance.',
                                ]);
                            }

                            if ($amount > $outstanding) {
                                throw ValidationException::withMessages([
                                    'amount' => 'The hostel payment cannot exceed the outstanding balance of ₦' . number_format($outstanding, 2) . '.',
                                ]);
                            }

                            $payment = HostelPayment::query()->create([
                                'student_id' => $student->id,
                                'recorded_by' => Auth::id(),
                                'amount' => $amount,
                                'payment_date' => $data['payment_date'],
                                'payment_method' => $data['payment_method'],
                                'status' => 'paid',
                                'receipt_number' => 'HREC-' . strtoupper(bin2hex(random_bytes(4))),
                                'receipt_evidence_path' => $data['receipt_evidence_path'] ?? null,
                                'notes' => $data['notes'] ?? null,
                            ]);

                            activity()
                                ->causedBy(Auth::user())
                                ->performedOn($student)
                                ->withProperties([
                                    'hostel_payment_id' => $payment->id,
                                    'amount' => $amount,
                                ])
                                ->log('hostel_payment_recorded');
                        });

                        Notification::make()
                            ->title('Hostel payment recorded')
                            ->body('₦' . number_format((float) $data['amount'], 2) . ' has been added to the hostel payment ledger.')
                            ->success()
                            ->send();
                    } catch (ValidationException $exception) {
                        throw $exception;
                    } catch (Throwable $exception) {
                        Log::error('Hostel payment recording failed.', [
                            'student_id' => $this->record->id,
                            'error' => $exception->getMessage(),
                        ]);

                        Notification::make()
                            ->title('Hostel payment failed')
                            ->body('Could not record the hostel payment. Please try again.')
                            ->danger()
                            ->send();
                    }
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

