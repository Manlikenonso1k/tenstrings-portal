<?php

namespace App\Support\Payments;

use App\Models\Student;
use App\Models\StudentCourseFee;
use Illuminate\Support\Facades\Log;

class PaymentGatewayManager
{
    public function __construct(
        private readonly TgiGateway $tgiGateway,
        private readonly PaystackGateway $paystackGateway,
    ) {
    }

    public function initialize(Student $student, StudentCourseFee $fee, string $email, float $amount): array
    {
        $reference = 'TEN-' . now()->format('YmdHis') . '-' . $student->id . '-' . random_int(1000, 9999);

        try {
            $result = $this->tgiGateway->initialize([
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $email,
                'amount' => (int) round($amount * 100),
                'reference' => $reference,
            ]);

            // Store metadata in session since TGI doesn't return it in callback
            session([
                'tgi_student_id_' . $reference => $student->id,
                'tgi_course_id_' . $reference => $fee->course_id,
                'tgi_fee_id_' . $reference => $fee->id,
                'tgi_amount_' . $reference => $amount,
            ]);

            return $result;
        } catch (\Throwable $exception) {
            Log::warning('TGI initialization failed, switching to Paystack backup.', [
                'error' => $exception->getMessage(),
                'student_id' => $student->id,
                'fee_id' => $fee->id,
            ]);

            // For Paystack fallback
            $basePayload = [
                'email' => $email,
                'amount' => (int) round($amount * 100),
                'currency' => 'NGN',
                'reference' => $reference,
                'callback_url' => route('portal.payments.callback'),
                'metadata' => [
                    'student_id' => $student->id,
                    'course_id' => $fee->course_id,
                    'fee_id' => $fee->id,
                ],
            ];

            return $this->paystackGateway->initialize($basePayload);
        }
    }

    public function verify(string $gateway, string $reference): array
    {
        if ($gateway === 'paystack') {
            return $this->paystackGateway->verify($reference);
        }

        return $this->tgiGateway->verify($reference);
    }
}
