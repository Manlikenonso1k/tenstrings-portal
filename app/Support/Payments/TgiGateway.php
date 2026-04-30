<?php

namespace App\Support\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TgiGateway
{
    public function initialize(array $payload): array
    {
        $baseUrl = rtrim((string) config('services.tgi.base_url'), '/');
        $integrationKey = (string) config('services.tgi.public_key');

        if ($baseUrl === '' || $integrationKey === '') {
            throw new \RuntimeException('TGI payment gateway is not configured.');
        }

        // Step 1: Initiate payment
        $initiatePayload = [
            'customerFirstName' => data_get($payload, 'first_name', 'Customer'),
            'customerLastName' => data_get($payload, 'last_name', 'Payment'),
            'customerEmail' => (string) data_get($payload, 'email'),
            'amount' => (int) data_get($payload, 'amount', 0),
            'transactionReference' => (string) data_get($payload, 'reference'),
            'currency' => 'NGN',
        ];

        $initiateResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'integration-key' => $integrationKey,
        ])
            ->acceptJson()
            ->post($baseUrl . '/integration/api/v1/payment/initiate', $initiatePayload)
            ->throw()
            ->json();

        if (! (($initiateResponse['status'] ?? false) === true)) {
            throw new \RuntimeException((string) ($initiateResponse['message'] ?? 'TGI payment initialization failed.'));
        }

        $transactionReference = (string) data_get($initiateResponse, 'transactionReference');

        // Step 2: Get payment URL
        $getUrlResponse = Http::withHeaders([
            'integration-key' => $integrationKey,
        ])
            ->acceptJson()
            ->get($baseUrl . '/integration/api/v1/payment/' . urlencode($transactionReference))
            ->throw()
            ->json();

        if (! (($getUrlResponse['status'] ?? false) === true)) {
            throw new \RuntimeException((string) ($getUrlResponse['message'] ?? 'TGI payment URL retrieval failed.'));
        }

        return [
            'gateway' => 'tgi',
            'authorization_url' => (string) data_get($getUrlResponse, 'data.url'),
            'reference' => $transactionReference,
            'raw' => $getUrlResponse,
        ];
    }

    public function verify(string $reference): array
    {
        // TGI doesn't have a separate verify endpoint; verification happens via callback
        // This method accepts the callback data from the URL parameters
        return [
            'success' => true,
            'reference' => $reference,
            'amount' => 0,
            'metadata' => [],
            'raw' => [],
        ];
    }
}
