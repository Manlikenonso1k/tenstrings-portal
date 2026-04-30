<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\StudentCourseFee;
use App\Support\Payments\PaymentGatewayManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OnlinePaymentController extends Controller
{
    public function initialize(StudentCourseFee $fee, Request $request, PaymentGatewayManager $manager): RedirectResponse
    {
        $student = $request->user()?->student;

        abort_if(! $student, 403);
        abort_if($fee->student_id !== $student->id, 403);
        abort_if((float) $fee->outstanding_balance <= 0, 422, 'No outstanding balance for this course.');

        $result = $manager->initialize(
            student: $student,
            fee: $fee,
            email: (string) $request->user()->email,
            amount: (float) $fee->outstanding_balance,
        );

        return redirect()->away($result['authorization_url']);
    }

    public function callback(Request $request): RedirectResponse
    {
        // TGI callback format: ?status=success|failed&ref=transactionReference&tgipay=1
        $status = (string) $request->query('status', '');
        $reference = (string) $request->query('ref', '');
        $tgipay = (string) $request->query('tgipay', '');

        if ($reference === '' || $tgipay !== '1') {
            return redirect('/portal/payments')->with('error', 'Invalid payment callback.');
        }

        if ($status !== 'success') {
            return redirect('/portal/payments')->with('error', 'Payment was not successful.');
        }

        try {
            // Extract metadata from reference (format: TEN-timestamp-studentId-randomInt)
            $parts = explode('-', $reference);
            if (count($parts) < 4 || $parts[0] !== 'TEN') {
                return redirect('/portal/payments')->with('error', 'Invalid transaction reference format.');
            }

            // Query to get fee by reference from session or recent payments
            // Since TGI doesn't return metadata in callback, we stored it in session
            $feeId = (int) session('tgi_fee_id_' . $reference);
            $studentId = (int) session('tgi_student_id_' . $reference);
            $courseId = (int) session('tgi_course_id_' . $reference);
            $amount = (float) session('tgi_amount_' . $reference);

            if (! $feeId || ! $studentId || ! $courseId) {
                return redirect('/portal/payments')->with('error', 'Unable to reconcile payment metadata.');
            }

            $fee = StudentCourseFee::query()->find($feeId);

            if (! $fee || $fee->student_id !== $studentId || $fee->course_id !== $courseId) {
                return redirect('/portal/payments')->with('error', 'Unable to reconcile payment metadata.');
            }

            if (Payment::query()->where('receipt_number', $reference)->exists()) {
                return redirect('/portal/payments')->with('status', 'Payment already recorded.');
            }

            $paymentStatus = $amount >= (float) $fee->outstanding_balance ? 'paid' : 'partial';

            Payment::query()->create([
                'student_id' => $studentId,
                'course_id' => $courseId,
                'amount_paid' => $amount,
                'payment_date' => now()->toDateString(),
                'payment_method' => 'card',
                'receipt_number' => $reference,
                'payment_status' => $paymentStatus,
                'notes' => 'Gateway: TGI',
            ]);

            // Clean up session
            session()->forget(['tgi_fee_id_' . $reference, 'tgi_student_id_' . $reference, 'tgi_course_id_' . $reference, 'tgi_amount_' . $reference]);

            return redirect('/portal/payments')->with('status', 'Payment successful.');
        } catch (\Throwable $exception) {
            return redirect('/portal/payments')->with('error', 'Payment verification failed: ' . $exception->getMessage());
        }
    }
}
