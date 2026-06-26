<?php

namespace App\Console\Commands;

use App\Jobs\SendWhatsAppMessage;
use App\Models\Student;
use Illuminate\Console\Command;

class SendFeeReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-fee-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send fee reminders to students owing money with staggered delays to avoid WhatsApp ban';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Finding students with pending balances...');

        // Query students owing money
        $students = Student::where('balance_due', '>', 0)
            ->whereNotNull('phone')
            ->get();

        if ($students->isEmpty()) {
            $this->info('No students found owing fees.');
            return;
        }

        $cumulativeDelay = 0;
        $count = 0;

        foreach ($students as $student) {
            $message = "Hello {$student->first_name}, this is a gentle reminder from Tenstrings Music Institute. Your current fee balance is NGN {$student->balance_due}. Please make a payment at your earliest convenience.";
            
            // Format phone number to standard international format without '+' if required by Evolution API
            $phone = preg_replace('/[^0-9]/', '', $student->phone);

            if ($cumulativeDelay == 0) {
                // First message sends immediately
                SendWhatsAppMessage::dispatch($phone, $message);
            } else {
                // Subsequent messages are delayed
                SendWhatsAppMessage::dispatch($phone, $message)->delay(now()->addSeconds($cumulativeDelay));
            }

            // Add a random delay between 5 and 10 seconds for the NEXT iteration
            $cumulativeDelay += rand(5, 10);
            $count++;
        }

        $this->info("Successfully queued {$count} fee reminder messages.");
    }
}
