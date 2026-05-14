<?php

namespace App\Filament\Portal\Pages;

use App\Models\Payment;
use App\Models\PaymentAdvice;
use App\Models\PortalSetting;
use App\Models\Student;
use App\Models\StudentCourseFee;
use App\Services\Payments\PaymentService;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PaymentsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'PAYMENTS';

    protected static string $view = 'filament.portal.pages.payments-page';

    protected function getViewData(): array
    {
        $user = Auth::user();
        $studentId = $user && $user->student ? $user->student->id : null;
        $student = $studentId ? Student::query()->find($studentId) : null;
        $outstandingBalance = $studentId
            ? (float) StudentCourseFee::query()->where('student_id', $studentId)->sum('outstanding_balance')
            : 0.0;
        $settings = PortalSetting::current();
        $paymentService = app(PaymentService::class);

        if ($studentId) {
            Payment::query()
                ->where('student_id', $studentId)
                ->where('gateway', 'tgipay')
                ->whereIn('status', ['pending', 'processing'])
                ->latest('id')
                ->get()
                ->each(function (Payment $payment) use ($paymentService, $student): void {
                    $paymentService->reconcileTgiPayPayment($payment->reference, [
                        'student_id' => $payment->student_id,
                        'course_id' => $payment->course_id,
                        'invoice_id' => $payment->invoice_id,
                        'amount' => (float) $payment->amount,
                        'customer_email' => (string) ($student ? $student->email : ''),
                        'metadata' => (array) $payment->metadata,
                    ], (string) $payment->status);
                });
        }

        return [
            'student' => $student,
            'outstandingBalance' => $outstandingBalance,
            'paystackEnabled' => $settings->gatewayEnabled('paystack-titan'),
            'tgipayEnabled' => $settings->gatewayEnabled('tgipay'),
            'allowPaymentReset' => $settings->allow_payment_reset && ($user && $user->isSuperAdmin()),
            'pendingAdvice' => $studentId
                ? PaymentAdvice::query()
                    ->with('course')
                    ->where('student_id', $studentId)
                    ->where('status', 'pending')
                    ->latest('id')
                    ->first()
                : null,
            'payments' => Payment::query()
                ->where('student_id', $studentId)
                ->latest('payment_date')
                ->get(),
        ];
    }
}
