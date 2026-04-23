<?php

namespace App\Services\Payments\Gateways;

use App\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class PaystackTitanGateway implements PaymentGatewayInterface
{
    public function initializePayment(array $data): array
    {
        $payload = [
            'email' => $data['email'],
            'amount' => (int) round(((float) $data['amount']) * 100),
            'reference' => $data['reference'],
            'metadata' => $data['metadata'] ?? [],
            'channels' => ['bank_transfer', 'card'],
            'callback_url' => $data['callback_url'] ?? null,
        ];

        $response = Http::withToken($this->secretKey())
            ->acceptJson()
            ->post($this->baseUrl() . '/transaction/initialize', array_filter($payload, fn ($value) => $value !== null));

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json() ?? [],
        ];
    }

    public function verifyPayment(string $reference): array
    {
        $response = Http::withToken($this->secretKey())
            ->acceptJson()
            ->get($this->baseUrl() . '/transaction/verify/' . $reference);

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json() ?? [],
        ];
    }

    public function handleWebhook(array $payload): array
    {
        $event = (string) Arr::get($payload, 'event', '');
        $data = (array) Arr::get($payload, 'data', []);
        $metadata = (array) Arr::get($data, 'metadata', []);

        $status = match ($event) {
            'charge.success' => 'success',
            'charge.failed' => 'failed',
            default => 'processing',
        };

        $reference = (string) Arr::get($data, 'reference', '');
        $amount = (float) Arr::get($data, 'amount', 0) / 100;
        $customerCode = (string) Arr::get($data, 'customer.customer_code', Arr::get($data, 'customer_code', ''));
        $customerEmail = (string) Arr::get($data, 'customer.email', '');
        $dedicatedAccount = Arr::get($data, 'dedicated_account', Arr::get($data, 'authorization', []));

        return [
            'event' => $event,
            'reference' => $reference,
            'status' => $status,
            'amount' => $amount,
            'currency' => Arr::get($data, 'currency', 'NGN'),
            'customer_code' => $customerCode,
            'customer_email' => $customerEmail,
            'invoice_id' => $metadata['invoice_id'] ?? null,
            'student_id' => $metadata['student_id'] ?? null,
            'metadata' => $metadata,
            'gateway_response' => $payload,
            'dedicated_account' => $dedicatedAccount,
        ];
    }

    public function isValidSignature(string $rawPayload, ?string $signature): bool
    {
        if (! $signature) {
            return false;
        }

        $computed = hash_hmac('sha512', $rawPayload, $this->webhookSecret());

        return hash_equals($computed, $signature);
    }

    private function baseUrl(): string
    {
        return rtrim((string) config('services.paystack.base_url', 'https://api.paystack.co'), '/');
    }

    private function secretKey(): string
    {
        return (string) config('services.paystack.secret_key', '');
    }

    private function webhookSecret(): string
    {
        return (string) config('services.paystack.webhook_secret', $this->secretKey());
    }
}
