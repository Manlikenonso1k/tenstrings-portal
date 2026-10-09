<?php

return [
    'approval_email' => env('PAYMENT_EVIDENCE_APPROVAL_EMAIL'),
    'otp_ttl_minutes' => (int) env('PAYMENT_EVIDENCE_OTP_TTL', 10),
    'max_attempts' => (int) env('PAYMENT_EVIDENCE_OTP_MAX_ATTEMPTS', 5),
];