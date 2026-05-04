<?php

namespace App\Services\Payments\Gateways;

use App\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TgiPayGateway implements PaymentGatewayInterface
{
    /**
     * Initiate payment via TGIPAY Direct Integration.
     *
     * Server-to-Server flow:
     * 1. POST to initiate endpoint with payment details
     * 2. GET to retrieve payment URL
     * 3. Redirect user to payment URL
     */
    public function initializePayment(array $data): array
    {
        $traceId = (string) ($data['trace_id'] ?? 'tgipay-init-missing-trace');
        $payload = [
            'customerFirstName' => $data['customer_first_name'] ?? '',
            'customerLastName' => $data['customer_last_name'] ?? '',
            'customerEmail' => $data['email'],
            'amount' => (float) $data['amount'],
            'transactionReference' => $data['reference'],
            'currency' => 'NGN',
        ];

        Log::info('TGIPAY initialize payment request', [
            'trace_id' => $traceId,
            'reference' => $payload['transactionReference'],
            'endpoint' => $this->baseUrl() . '/payment/initiate',
            'has_integration_key' => $this->integrationKey() !== '',
            'amount' => $payload['amount'],
        ]);

        $response = Http::withHeaders([
            'integration-key' => $this->integrationKey(),
        ])
            ->acceptJson()
            ->timeout(30)
            ->post($this->baseUrl() . '/payment/initiate', $payload);

        if (! $response->successful()) {
            Log::warning('TGIPAY initialize payment rejected', [
                'trace_id' => $traceId,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
        }

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json() ?? [],
        ];
    }

    /**
     * Verify payment status with TGIPAY.
     */
    public function verifyPayment(string $reference, string $traceId = ''): array
    {
        $traceId = $traceId !== '' ? $traceId : 'tgipay-verify-' . $reference;

        Log::info('TGIPAY payment verification request', [
            'trace_id' => $traceId,
            'reference' => $reference,
            'endpoint' => $this->baseUrl() . '/payment/status/' . $reference,
            'has_integration_key' => $this->integrationKey() !== '',
        ]);

        $response = Http::withHeaders([
            'integration-key' => $this->integrationKey(),
        ])
            ->acceptJson()
            ->timeout(30)
            ->get($this->baseUrl() . '/payment/status/' . $reference);

        if (! $response->successful()) {
            Log::warning('TGIPAY payment verification rejected', [
                'trace_id' => $traceId,
                'reference' => $reference,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
        }

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json() ?? [],
        ];
    }

    /**
     * Handle webhook callback from TGIPAY.
     *
     * Expected payload parameters:
     * - status: 'success', 'failed', 'completed', or related final status
     * - ref: transaction reference
     */
    public function handleWebhook(array $payload): array
    {
        $status = (string) Arr::get($payload, 'status', 'processing');
        $reference = (string) Arr::get($payload, 'ref', '');

        // Map TGIPAY status to internal status
        $internalStatus = match ($status) {
            'success', 'successful', 'completed', 'paid' => 'success',
            'failed', 'cancelled', 'canceled' => 'failed',
            default => 'processing',
        };

        return [
            'event' => 'payment.status',
            'reference' => $reference,
            'status' => $internalStatus,
            'amount' => (float) Arr::get($payload, 'amount', 0),
            'currency' => Arr::get($payload, 'currency', 'NGN'),
            'customer_email' => Arr::get($payload, 'customer_email', ''),
            'student_id' => Arr::get($payload, 'student_id'),
            'course_id' => Arr::get($payload, 'course_id'),
            'invoice_id' => Arr::get($payload, 'invoice_id'),
            'metadata' => Arr::get($payload, 'metadata', []),
            'gateway_response' => $payload,
        ];
    }

    private function integrationKey(): string
    {
        return (string) config('services.tgipay.integration_key', env('TGIPAY_INTEGRATION_KEY'));
    }

    private function baseUrl(): string
    {
        return (string) config('services.tgipay.base_url', 'https://integration-service.tgipay.com/integration/api/v1');
    }
}
