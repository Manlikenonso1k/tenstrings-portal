<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\Payments\PaymentService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\URL;

class InvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';

    protected static ?string $recordTitleAttribute = 'quarter_name';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('quarter_name')
                ->required()
                ->maxLength(30),
            Forms\Components\TextInput::make('amount')
                ->label('Amount (NGN)')
                ->numeric()
                ->prefix('₦')
                ->required()
                ->minValue(1),
            Forms\Components\Select::make('status')
                ->options([
                    'unpaid' => 'Unpaid',
                    'paid' => 'Paid',
                ])
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('quarter_name')
            ->columns([
                Tables\Columns\TextColumn::make('quarter_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('amount')->money('NGN')->label('Amount (₦)')->sortable(),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'warning' => 'unpaid',
                    'success' => 'paid',
                ]),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('generateFutureQuarterInvoice')
                    ->label('Generate Future Quarter Invoice')
                    ->icon('heroicon-o-calendar-days')
                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->label('Amount (NGN)')
                            ->prefix('₦')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Forms\Components\Select::make('future_offset')
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
                            (int) $this->ownerRecord->id,
                            (float) $data['amount'],
                            (int) $data['future_offset']
                        );

                        Notification::make()
                            ->title('Future quarter invoice created')
                            ->body('Invoice ' . $invoice->quarter_name . ' has been generated.')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('downloadInvoice')
                    ->label('Download Invoice')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Invoice $record): string => URL::temporarySignedRoute(
                        'documents.invoices.download',
                        now()->addMinutes(15),
                        ['invoice' => $record->id]
                    ))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('downloadReceipt')
                    ->label('Download Receipt')
                    ->icon('heroicon-o-document-arrow-down')
                    ->visible(fn (Invoice $record): bool => Payment::query()
                        ->where('invoice_id', $record->id)
                        ->where('status', 'success')
                        ->exists())
                    ->url(function (Invoice $record): ?string {
                        $paymentId = Payment::query()
                            ->where('invoice_id', $record->id)
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
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->latest('id'));
    }
}
