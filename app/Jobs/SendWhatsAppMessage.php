<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phoneNumber;
    public $message;

    /**
     * Create a new job instance.
     */
    public function __construct(string $phoneNumber, string $message)
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $apiUrl = config('services.evolution.url') . '/message/sendText/tenstrings-alerts';
        $apiToken = config('services.evolution.token');

        try {
            $response = Http::withToken($apiToken)
                ->timeout(15)
                ->post($apiUrl, [
                    'number' => $this->phoneNumber,
                    'text' => $this->message,
                ]);

            if ($response->failed()) {
                Log::error('Evolution API Failed to send message', [
                    'phone' => $this->phoneNumber,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Evolution API Exception', [
                'phone' => $this->phoneNumber,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
