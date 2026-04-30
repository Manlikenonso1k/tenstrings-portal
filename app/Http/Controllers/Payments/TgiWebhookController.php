<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\StudentCourseFee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TgiWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        // TGI webhook payload structure (server-to-server)
        $status = (string) ($request->input('status') ?? '');
        $reference = (string) ($request->input('transactionReference') ?? $request->input('reference') ?? '');
        $amount = (float) ($request->input('amount') ?? 0);

        Log::info('TGI webhook received', [
            'status' => $status,
            'reference' => $reference,
            'amount' => $amount,
            'payload' => $request->all(),
        ]);

        if ($reference === '') {
            Log::warning('TGI webhook: missing transaction reference', ['payload' => $request->all()]);
            return response('Invalid reference', 400);
        }

        if ($status !== 'success') {
            Log::info('TGI webhook: payment not successful', ['status' => $status, 'reference' => $reference]);
            return response('Payment not successful', 200);
        }

        try {
            // Check if payment already recorded
            if (Payment::query()->where('receipt_number', $reference)->exists()) {
                Log::info('TGI webhook: payment already recorded', ['reference' => $reference]);
                return response('Payment already recorded', 200);
            }

            // Extract metadata from reference (format: TEN-timestamp-studentId-randomInt)
            $parts = explode('-', $reference);
            if (count($parts) < 4 || $parts[0] !== 'TEN') {
                Log::warning('TGI webhook: invalid reference format', ['reference' => $reference]);
                return response('Invalid reference format', 400);
            }

            // Try to get metadata from session or recent payments
            $feeId = (int) session('tgi_fee_id_' . $reference);
            $studentId = (int) session('tgi_student_id_' . $reference);
            $courseId = (int) session('tgi_course_id_' . $reference);
            $sessionAmount = (float) session('tgi_amount_' . $reference);

            // If not in session, try to infer from recent records
            if (! $feeId) {
                Log::warning('TGI webhook: metadata not found in session', ['reference' => $reference]);
                return response('Metadata not found', 422);
            }

            $fee = StudentCourseFee::query()->find($feeId);

            if (! $fee || $fee->student_id !== $studentId || $fee->course_id !== $courseId) {
                Log::warning('TGI webhook: fee/student/course mismatch', [
                    'feeId' => $feeId,
                    'studentId' => $studentId,
                    'courseId' => $courseId,
                    'reference' => $reference,
                ]);
                return response('Fee mismatch', 422);
            }

            // Use amount from webhook, fallback to session amount
            $paymentAmount = $amount > 0 ? $amount : $sessionAmount;

            if ($paymentAmount <= 0) {
                Log::warning('TGI webhook: invalid payment amount', ['amount' => $paymentAmount, 'reference' => $reference]);
                return response('Invalid amount', 400);
            }

            $paymentStatus = $paymentAmount >= (float) $fee->outstanding_balance ? 'paid' : 'partial';

            Payment::query()->create([
                'student_id' => $studentId,
                'course_id' => $courseId,
                'amount_paid' => $paymentAmount,
                'payment_date' => now()->toDateString(),
                'payment_method' => 'card',
                'receipt_number' => $reference,
                'payment_status' => $paymentStatus,
                'notes' => 'Gateway: TGI (webhook)',
            ]);

            // Clean up session
            session()->forget([
                'tgi_fee_id_' . $reference,
                'tgi_student_id_' . $reference,
                'tgi_course_id_' . $reference,
                'tgi_amount_' . $reference,
            ]);

            Log::info('TGI webhook: payment recorded', [
                'reference' => $reference,
                'studentId' => $studentId,
                'amount' => $paymentAmount,
                'status' => $paymentStatus,
            ]);

            return response('Success', 200);
        } catch (\Throwable $exception) {
            Log::error('TGI webhook: exception', [
                'reference' => $reference,
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);

            return response('Error processing webhook', 500);
        }
    }
}
