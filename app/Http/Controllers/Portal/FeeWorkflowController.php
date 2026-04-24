<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentAdvice;
use App\Models\StudentCourseFee;
use App\Services\Payments\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeeWorkflowController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
    ) {
    }

    public function generatePage(Request $request): View|RedirectResponse
    {
        $student = $request->user()?->student;

        if (! $student) {
            abort(403);
        }

        $pendingAdvice = PaymentAdvice::query()
            ->with('course')
            ->where('student_id', $student->id)
            ->where('status', 'pending')
            ->latest('id')
            ->first();

        return view('fees.generate', [
            'student' => $student,
            'pendingAdvice' => $pendingAdvice,
            'year' => (int) now()->year,
            'quarters' => [
                ['month' => 2, 'label' => 'February'],
                ['month' => 5, 'label' => 'May'],
                ['month' => 8, 'label' => 'August'],
                ['month' => 11, 'label' => 'November'],
            ],
        ]);
    }

    public function generateAdvice(Request $request): RedirectResponse
    {
        $student = $request->user()?->student;

        if (! $student) {
            abort(403);
        }

        $existingPending = PaymentAdvice::query()
            ->where('student_id', $student->id)
            ->where('status', 'pending')
            ->latest('id')
            ->first();

        // Block duplicate pending advice so one payment intent is active at a time.
        if ($existingPending) {
            return redirect()->route('fees.advice.current')
                ->with('status', 'You already have a pending payment advice. Complete payment or contact admin to cancel it.');
        }

        $validated = $request->validate([
            'quarter_month' => ['required', 'integer', 'in:2,5,8,11'],
            'year' => ['required', 'integer', 'min:2020', 'max:2100'],
        ]);

        $fee = StudentCourseFee::query()
            ->with('course')
            ->where('student_id', $student->id)
            ->orderByRaw('CASE WHEN outstanding_balance > 0 THEN 0 ELSE 1 END')
            ->orderByDesc('outstanding_balance')
            ->latest('id')
            ->first();

        if (! $fee) {
            return back()->withErrors([
                'quarter_month' => 'No course fee record found. Please contact admin to assign your course fee.',
            ]);
        }

        $amount = (float) ($fee->outstanding_balance > 0 ? $fee->outstanding_balance : $fee->total_course_fee);

        if ($amount <= 0) {
            return back()->withErrors([
                'quarter_month' => 'Unable to generate advice with zero amount.',
            ]);
        }

        PaymentAdvice::query()->create([
            'student_id' => $student->id,
            'course_id' => $fee->course_id,
            'quarter_month' => (int) $validated['quarter_month'],
            'year' => (int) $validated['year'],
            'quarter_name' => $this->quarterName((int) $validated['quarter_month'], (int) $validated['year']),
            'amount' => $amount,
            'status' => 'pending',
            'generated_at' => now(),
        ]);

        return redirect()->route('fees.advice.current')
            ->with('status', 'Payment advice generated successfully.');
    }

    public function currentAdvice(Request $request): View
    {
        $student = $request->user()?->student;

        if (! $student) {
            abort(403);
        }

        $advice = PaymentAdvice::query()
            ->with('course')
            ->where('student_id', $student->id)
            ->where('status', 'pending')
            ->latest('id')
            ->first();

        return view('fees.current-advice', [
            'student' => $student,
            'advice' => $advice,
        ]);
    }

    public function payOnline(Request $request): RedirectResponse
    {
        $student = $request->user()?->student;

        if (! $student) {
            abort(403);
        }

        $advice = PaymentAdvice::query()
            ->where('student_id', $student->id)
            ->where('status', 'pending')
            ->latest('id')
            ->first();

        if (! $advice) {
            return redirect()->route('fees.generate')
                ->withErrors(['payment' => 'No pending advice found. Generate fees first.']);
        }

        if (! is_string($student->email) || ! filter_var($student->email, FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors([
                'payment' => 'Cannot start payment because your student email is missing or invalid. Please contact admin.',
            ]);
        }

        $result = $this->paymentService->initializePayment('paystack-titan', [
            'student_id' => (int) $student->id,
            'amount' => (float) $advice->amount,
            'invoice_amount' => (float) $advice->amount,
            'course_id' => $advice->course_id,
            'quarter_name' => $advice->quarter_name,
            'callback_url' => route('portal.payments.callback'),
            'metadata' => [
                'payment_advice_id' => $advice->id,
                'advice_year' => $advice->year,
                'advice_quarter_month' => $advice->quarter_month,
            ],
        ]);

        $authorizationUrl = data_get($result, 'gateway_response.body.data.authorization_url');

        if (! is_string($authorizationUrl) || $authorizationUrl === '') {
            $gatewayMessage = (string) (
                data_get($result, 'gateway_response.body.message')
                ?? data_get($result, 'gateway_response.body.data.message')
                ?? data_get($result, 'gateway_response.body.error')
                ?? 'Unable to start payment right now. Please try again.'
            );

            return back()->withErrors([
                'payment' => $gatewayMessage,
            ]);
        }

        return redirect()->away($authorizationUrl);
    }

    public function receipts(Request $request): View
    {
        $student = $request->user()?->student;

        if (! $student) {
            abort(403);
        }

        $payments = Payment::query()
            ->where('student_id', $student->id)
            ->where('status', 'success')
            ->latest('processed_at')
            ->get();

        return view('fees.receipts', [
            'payments' => $payments,
        ]);
    }

    private function quarterName(int $month, int $year): string
    {
        return match ($month) {
            2 => 'Q1-' . $year,
            5 => 'Q2-' . $year,
            8 => 'Q3-' . $year,
            11 => 'Q4-' . $year,
            default => 'Q1-' . $year,
        };
    }
}
