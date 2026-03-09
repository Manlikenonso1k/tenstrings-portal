<?php

namespace App\Filament\Portal\Pages;

use App\Models\Payment;
use Filament\Pages\Page;

class PaymentsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'PAYMENTS';

    protected static string $view = 'filament.portal.pages.payments-page';

    protected function getViewData(): array
    {
        $studentId = auth()->user()?->student?->id;

        return [
            'payments' => Payment::query()
                ->where('student_id', $studentId)
                ->latest('payment_date')
                ->get(),
        ];
    }
}
