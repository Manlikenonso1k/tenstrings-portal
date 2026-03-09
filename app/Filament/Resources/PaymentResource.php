<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\StudentCourseFee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Payment Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('student_id')->relationship('student', 'student_number')->searchable()->preload()->required(),
            Forms\Components\Select::make('course_id')->relationship('course', 'name')->searchable()->preload(),
            Forms\Components\TextInput::make('amount_paid')->numeric()->required()->minValue(0.01),
            Forms\Components\DatePicker::make('payment_date')->required()->default(now()),
            Forms\Components\Select::make('payment_method')->options([
                'cash' => 'Cash',
                'card' => 'Card',
                'transfer' => 'Transfer',
                'cheque' => 'Cheque',
            ])->required(),
            Forms\Components\TextInput::make('receipt_number')->maxLength(255),
            Forms\Components\Select::make('payment_status')->options([
                'paid' => 'Paid',
                'partial' => 'Partial',
                'pending' => 'Pending',
            ])->required(),
            Forms\Components\Textarea::make('notes')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_number')->searchable(),
                Tables\Columns\TextColumn::make('student.student_number')->label('Student ID')->searchable(),
                Tables\Columns\TextColumn::make('course.name')->searchable(),
                Tables\Columns\TextColumn::make('amount_paid')->money('NGN'),
                Tables\Columns\TextColumn::make('payment_date')->date(),
                Tables\Columns\BadgeColumn::make('payment_status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'student'], true);
    }

    public static function validatePaymentDoesNotExceedBalance(array $data): void
    {
        if (! isset($data['student_id'], $data['course_id'], $data['amount_paid'])) {
            return;
        }

        $fee = StudentCourseFee::query()
            ->where('student_id', $data['student_id'])
            ->where('course_id', $data['course_id'])
            ->first();

        if (! $fee) {
            return;
        }

        if ((float) $data['amount_paid'] > (float) $fee->outstanding_balance) {
            Notification::make()
                ->title('Invalid payment amount')
                ->body('Payment cannot exceed outstanding balance.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'amount_paid' => 'Amount cannot exceed outstanding balance.',
            ]);
        }
    }
}
