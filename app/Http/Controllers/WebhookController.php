<?php

namespace App\Http\Controllers;

use App\Services\Payments\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService)
    {
    }

    public function handle(Request $request, string $gateway): JsonResponse
    {
        $rawPayload = (string) $request->getContent();
        $payload = $request->all();

        $client = $this->paymentService->gateway($gateway);

        $signature = $request->header('x-paystack-signature');

        if (! $client->isValidSignature($rawPayload, $signature)) {
            Log::warning('Invalid payment webhook signature.', [
                'gateway' => $gateway,
                'ip' => $request->ip(),
            ]);

            // Keep webhook response 200 to avoid endless retries from gateway.
            return response()->json([
                'ok' => false,
                'message' => 'Signature validation failed.',
            ], 200);
        }

        $result = $this->paymentService->handleWebhook($gateway, $payload);

        return response()->json([
            'ok' => true,
            'message' => 'Webhook processed.',
            'data' => $result,
        ], 200);
    }
}
