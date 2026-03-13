<?php

namespace App\Filament\Portal\Pages;

use App\Models\Payment;
use App\Models\Student;
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

        return [
            'student' => $student,
            'payments' => Payment::query()
                ->where('student_id', $studentId)
                ->latest('payment_date')
                ->get(),
        ];
    }
}
