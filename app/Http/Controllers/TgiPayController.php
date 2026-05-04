<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentAdvice;
use App\Models\Student;
use App\Services\Payments\Gateways\TgiPayGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TgiPayController extends Controller
{
    public function __construct(
        private readonly TgiPayGateway $tgiPayGateway,
    ) {
    }

    /**
     * Initiate a TGIPAY payment.
     *
     * Server-to-Server flow:
     * 1. POST to TGIPAY initiate endpoint with payment details
     * 2. GET to retrieve the payment URL
     * 3. Redirect student to the payment URL
     */
    public function initiatePayment(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'advice_id' => ['required', 'integer', 'exists:payment_advices,id'],
        ]);

        $student = $request->user()?->student;

        if (! $student) {
            return $this->errorJson('Unauthorized', 403);
        }

        $advice = PaymentAdvice::query()
            ->with('course')
            ->where('id', (int) $validated['advice_id'])
            ->where('student_id', $student->id)
            ->where('status', 'pending')
            ->first();

        if (! $advice) {
            return back()->withErrors(['payment' => 'Payment advice not found or already processed.']);
        }

        if (! is_string($student->email) || ! filter_var($student->email, FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors(['payment' => 'Student email is missing or invalid. Please contact admin.']);
        }

        try {
            // Step 1: Generate reference
            $reference = 'TGIPAY-' . date('Ymdhis') . '-' . $student->id;
            $traceId = 'tgipay-init-' . $reference;

            Log::withContext([
                'trace_id' => $traceId,
                'gateway' => 'tgipay',
                'reference' => $reference,
                'student_id' => $student->id,
                'advice_id' => $advice->id,
            ]);

            // Step 2: Create Payment record to track the transaction
            $payment = Payment::query()->create([
                'user_id' => $student->user_id,
                'student_id' => $student->id,
                'invoice_id' => null,
                'gateway' => 'tgipay',
                'reference' => $reference,
                'course_id' => $advice->course_id,
                'amount' => (float) $advice->amount,
                'status' => 'pending',
                'payment_status' => 'pending',
                'amount_paid' => 0,
                'payment_date' => now()->toDateString(),
                'payment_method' => 'transfer',
                'metadata' => [
                    'advice_id' => $advice->id,
                    'student_id' => $student->id,
                    'course_id' => $advice->course_id,
                ],
            ]);

            // Step 3: Initiate payment with TGIPAY
            $nameParts = explode(' ', trim($student->name ?? ''), 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? $firstName;

            $initResponse = $this->tgiPayGateway->initializePayment([
                'customer_first_name' => $firstName,
                'customer_last_name' => $lastName,
                'email' => $student->email,
                'amount' => (float) $advice->amount,
                'reference' => $reference,
                'trace_id' => $traceId,
            ]);

            if (! $initResponse['ok']) {
                $payment->update(['status' => 'failed', 'gateway_response' => $initResponse['body'] ?? []]);

                Log::error('TGIPAY payment initiation failed', [
                    'student_id' => $student->id,
                    'reference' => $reference,
                    'response' => $initResponse,
                ]);

                return back()->withErrors(['payment' => 'Unable to initiate payment with TGIPAY. Please try again later.']);
            }

            // Step 4: Use checkout URL returned by TGIPAY initiate response
            $paymentUrl = data_get($initResponse, 'body.data.url');

            if (! is_string($paymentUrl) || $paymentUrl === '') {
                $payment->update(['status' => 'failed', 'gateway_response' => $initResponse['body'] ?? []]);

                Log::error('TGIPAY checkout URL not found in initiate response', [
                    'student_id' => $student->id,
                    'reference' => $reference,
                    'response' => $initResponse,
                ]);

                return back()->withErrors(['payment' => 'Unable to retrieve payment URL. Please try again later.']);
            }

            // Update payment record with gateway response
            $payment->update([
                'status' => 'processing',
                'gateway_response' => $initResponse['body'] ?? [],
            ]);

            Log::info('TGIPAY payment initiated', [
                'student_id' => $student->id,
                'reference' => $reference,
                'amount' => $advice->amount,
            ]);

            // Step 5: Redirect student to TGIPAY payment URL
            return redirect()->away($paymentUrl);
        } catch (\Exception $e) {
            Log::error('TGIPAY payment initiation exception', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['payment' => 'An error occurred while initiating payment. Please try again later.']);
        }
    }

    /**
     * Handle TGIPAY payment callback.
     *
     * Expected parameters:
     * - status: 'success', 'failed', 'completed', or related final status
     * - ref: transaction reference
     */
    public function callback(Request $request): RedirectResponse
    {
        $status = (string) $request->query('status', '');
        $reference = (string) $request->query('ref', '');
        $traceId = $reference !== '' ? 'tgipay-callback-' . $reference : 'tgipay-callback-missing-reference';

        Log::withContext([
            'trace_id' => $traceId,
            'gateway' => 'tgipay',
            'reference' => $reference,
        ]);

        if ($status === '' || $reference === '') {
            Log::warning('TGIPAY callback with missing parameters', [
                'status' => $status,
                'reference' => $reference,
            ]);

            return redirect('/portal/payments')
                ->with('error', 'Invalid payment callback parameters.');
        }

        try {
            // Verify payment with TGIPAY
            $verifyResponse = $this->tgiPayGateway->verifyPayment($reference, $traceId);
            $verifiedStatus = strtolower((string) data_get($verifyResponse, 'body.data.paymentStatus', data_get($verifyResponse, 'body.data.status', $status)));
            $finalStatus = match ($verifiedStatus) {
                'success', 'successful', 'completed', 'paid' => 'success',
                'failed', 'cancelled', 'canceled' => 'failed',
                default => 'processing',
            };

            Log::info('TGIPAY payment callback received', [
                'reference' => $reference,
                'status' => $status,
                'verified_status' => $verifiedStatus,
                'verify_ok' => $verifyResponse['ok'],
            ]);

            // Find and update the Payment record
            $payment = Payment::query()
                ->where('reference', $reference)
                ->where('gateway', 'tgipay')
                ->first();

            if (! $payment) {
                Log::warning('TGIPAY callback payment record not found', [
                    'reference' => $reference,
                ]);

                return redirect('/portal/payments')
                    ->with('error', 'Payment record not found.');
            }

            if ($finalStatus === 'success') {
                $payment->update([
                    'status' => 'success',
                    'payment_status' => 'paid',
                    'amount_paid' => $payment->amount,
                    'gateway_response' => $verifyResponse['body'] ?? [],
                    'processed_at' => now(),
                ]);

                // Update PaymentAdvice status if it exists
                if (isset($payment->metadata['advice_id'])) {
                    PaymentAdvice::query()
                        ->where('id', (int) $payment->metadata['advice_id'])
                        ->update(['status' => 'paid', 'paid_at' => now()]);
                }

                Log::info('TGIPAY payment marked as successful', [
                    'reference' => $reference,
                    'student_id' => $payment->student_id,
                ]);

                return redirect('/portal/payments')
                    ->with('status', 'Payment successful. Your payment advice has been updated.');
            }

            if ($finalStatus === 'failed') {
                $payment->update([
                    'status' => 'failed',
                    'payment_status' => 'failed',
                    'gateway_response' => $verifyResponse['body'] ?? [],
                    'processed_at' => now(),
                ]);

                Log::info('TGIPAY payment marked as failed', [
                    'reference' => $reference,
                    'student_id' => $payment->student_id,
                ]);

                return redirect('/portal/payments')
                    ->with('error', 'Payment failed. Please try again.');
            }

            $payment->update([
                'status' => 'processing',
                'payment_status' => 'pending',
                'gateway_response' => $verifyResponse['body'] ?? [],
                'processed_at' => now(),
            ]);

            return redirect('/portal/payments')
                ->with('status', 'Payment is still processing. Please check again shortly.');
        } catch (\Exception $e) {
            Log::error('TGIPAY callback processing exception', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return redirect('/portal/payments')
                ->with('error', 'An error occurred while processing your payment.');
        }
    }

    private function errorJson(string $message, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'ok' => false,
            'message' => $message,
        ], $statusCode);
    }
}
