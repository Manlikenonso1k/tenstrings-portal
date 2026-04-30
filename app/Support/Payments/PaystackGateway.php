<?php

namespace App\Support\Payments;

use Illuminate\Support\Facades\Http;

class PaystackGateway
{
    public function initialize(array $payload): array
    {
        $baseUrl = rtrim((string) config('services.paystack.base_url', 'https://api.paystack.co'), '/');
        $secretKey = (string) config('services.paystack.secret_key');

        if ($secretKey === '') {
            throw new \RuntimeException('Paystack gateway is not configured.');
        }

        $response = Http::withToken($secretKey)
            ->acceptJson()
            ->post($baseUrl . '/transaction/initialize', $payload)
            ->throw()
            ->json();

        if (! (($response['status'] ?? false) === true)) {
            throw new \RuntimeException((string) ($response['message'] ?? 'Paystack payment initialization failed.'));
        }

        return [
            'gateway' => 'paystack',
            'authorization_url' => (string) data_get($response, 'data.authorization_url'),
            'reference' => (string) data_get($response, 'data.reference'),
            'raw' => $response,
        ];
    }

    public function verify(string $reference): array
    {
        $baseUrl = rtrim((string) config('services.paystack.base_url', 'https://api.paystack.co'), '/');
        $secretKey = (string) config('services.paystack.secret_key');

        if ($secretKey === '') {
            throw new \RuntimeException('Paystack gateway is not configured.');
        }

        $response = Http::withToken($secretKey)
            ->acceptJson()
            ->get($baseUrl . '/transaction/verify/' . urlencode($reference))
            ->throw()
            ->json();

        if (! (($response['status'] ?? false) === true)) {
            throw new \RuntimeException((string) ($response['message'] ?? 'Paystack payment verification failed.'));
        }

        return [
            'success' => data_get($response, 'data.status') === 'success',
            'reference' => (string) data_get($response, 'data.reference', $reference),
            'amount' => (float) data_get($response, 'data.amount', 0) / 100,
            'metadata' => (array) data_get($response, 'data.metadata', []),
            'raw' => $response,
        ];
    }
}
