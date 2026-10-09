<?php

namespace App\Services\Payments;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class PaymentEvidenceApprovalService
{
    public function request(Payment $payment, User $requester, string $replacementPath): void
    {
        $approvalEmail = (string) config('payment-evidence.approval_email');

        if (! filter_var($approvalEmail, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('The developer approval email has not been configured.');
        }

        $code = (string) random_int(100000, 999999);

        Cache::put($this->cacheKey($payment, $requester), [
            'code_hash' => Hash::make($code),
            'replacement_path' => $replacementPath,
            'attempts' => 0,
        ], now()->addMinutes((int) config('payment-evidence.otp_ttl_minutes', 10)));

        $student = $payment->student;

        Mail::raw(
            "A receipt-evidence replacement requires your approval.\n\n" .
            "Student: {$student?->full_name}\n" .
            "Matric number: {$student?->student_number}\n" .
            "Payment: {$payment->payment_number}\n" .
            "Requested by: {$requester->name} ({$requester->email})\n\n" .
            "Approval code: {$code}\n\n" .
            'This code expires in ' . config('payment-evidence.otp_ttl_minutes', 10) . ' minutes. Do not share it unless you approve this receipt replacement.',
            function ($message) use ($approvalEmail, $payment): void {
                $message
                    ->to($approvalEmail)
                    ->subject('Tenstrings payment evidence approval: ' . $payment->payment_number);
            }
        );

        activity()
            ->causedBy($requester)
            ->performedOn($student)
            ->withProperties([
                'payment_id' => $payment->id,
                'payment_number' => $payment->payment_number,
                'student_name' => $student?->full_name,
                'student_number' => $student?->student_number,
                'replacement_path' => $replacementPath,
            ])
            ->log('payment_evidence_replacement_requested');
    }

    public function replace(Payment $payment, User $requester, string $code): void
    {
        $key = $this->cacheKey($payment, $requester);
        $approval = Cache::get($key);

        if (! is_array($approval)) {
            throw new RuntimeException('No active approval request was found. Request a new code.');
        }

        if ((int) ($approval['attempts'] ?? 0) >= (int) config('payment-evidence.max_attempts', 5)) {
            Cache::forget($key);

            throw new RuntimeException('Too many invalid codes. Request a new code.');
        }

        if (! Hash::check($code, (string) ($approval['code_hash'] ?? ''))) {
            $approval['attempts'] = (int) ($approval['attempts'] ?? 0) + 1;
            Cache::put($key, $approval, now()->addMinutes((int) config('payment-evidence.otp_ttl_minutes', 10)));

            throw new RuntimeException('The approval code is invalid.');
        }

        $oldPath = $payment->receipt_evidence_path;
        $payment->forceFill([
            'receipt_evidence_path' => $approval['replacement_path'],
        ])->save();

        Cache::forget($key);

        $student = $payment->student;

        activity()
            ->causedBy($requester)
            ->performedOn($student)
            ->withProperties([
                'payment_id' => $payment->id,
                'payment_number' => $payment->payment_number,
                'student_name' => $student?->full_name,
                'student_number' => $student?->student_number,
                'old_receipt_evidence_path' => $oldPath,
                'new_receipt_evidence_path' => $payment->receipt_evidence_path,
            ])
            ->log('payment_evidence_replaced_with_developer_approval');
    }

    private function cacheKey(Payment $payment, User $requester): string
    {
        return 'payment-evidence-approval:' . $payment->getKey() . ':' . $requester->getKey();
    }
}