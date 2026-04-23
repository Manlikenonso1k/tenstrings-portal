<?php

namespace App\Filament\Portal\Pages;

use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentCourseFee;
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
        $studentId = Auth::user()?->student?->id;
        $student = $studentId ? Student::query()->find($studentId) : null;
        $outstandingBalance = $studentId
            ? (float) StudentCourseFee::query()->where('student_id', $studentId)->sum('outstanding_balance')
            : 0.0;

        return [
            'student' => $student,
            'outstandingBalance' => $outstandingBalance,
            'payments' => Payment::query()
                ->where('student_id', $studentId)
                ->latest('payment_date')
                ->get(),
        ];
    }
}
