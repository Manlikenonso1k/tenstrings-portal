<?php

namespace App\Filament\Portal\Pages;

use App\Models\Payment;
use App\Models\StudentCourseFee;
use App\Support\Payments\PaymentGatewayManager;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PaymentsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'PAYMENTS';

    protected static string $view = 'filament.portal.pages.payments-page';

    public function payFee(int $feeId): mixed
    {
        $student = auth()->user()?->student;

        if (! $student) {
            Notification::make()
                ->title('Student profile not found')
                ->danger()
                ->send();

            return null;
        }

        $fee = StudentCourseFee::query()
            ->where('id', $feeId)
            ->where('student_id', $student->id)
            ->where('outstanding_balance', '>', 0)
            ->first();

        if (! $fee) {
            Notification::make()
                ->title('No outstanding fee found for this course')
                ->danger()
                ->send();

            return null;
        }

        try {
            $result = app(PaymentGatewayManager::class)->initialize(
                student: $student,
                fee: $fee,
                email: (string) auth()->user()->email,
                amount: (float) $fee->outstanding_balance,
            );

            return redirect()->away($result['authorization_url']);
        } catch (\Throwable $exception) {
            Notification::make()
                ->title('Unable to start payment')
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return null;
        }
    }

    protected function getViewData(): array
    {
        $studentId = auth()->user()?->student?->id;

        return [
            'fees' => StudentCourseFee::query()
                ->with('course')
                ->where('student_id', $studentId)
                ->where('outstanding_balance', '>', 0)
                ->orderByDesc('created_at')
                ->get(),
            'payments' => Payment::query()
                ->where('student_id', $studentId)
                ->latest('payment_date')
                ->get(),
        ];
    }
}
