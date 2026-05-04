<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    public function initializePayment(array $data): array;

    public function verifyPayment(string $reference, string $traceId = ''): array;

    public function handleWebhook(array $payload): array;
}
