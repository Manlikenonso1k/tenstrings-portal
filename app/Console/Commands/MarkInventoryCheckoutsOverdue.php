<?php

namespace App\Console\Commands;

use App\Notifications\InventoryCheckoutOverdue;
use App\Services\Inventory\InventoryCheckoutService;
use Illuminate\Console\Command;

class MarkInventoryCheckoutsOverdue extends Command
{
    protected $signature = 'inventory:mark-overdue';

    protected $description = 'Flag event checkouts past their expected return and notify the branch';

    public function handle(InventoryCheckoutService $service): int
    {
        $checkouts = $service->markOverdue();

        if ($checkouts->isEmpty()) {
            $this->info('No checkouts are overdue.');

            return self::SUCCESS;
        }

        $notified = 0;

        foreach ($checkouts as $checkout) {
            foreach ($service->branchWatchers((int) $checkout->branch_id) as $user) {
                $user->notify(new InventoryCheckoutOverdue($checkout));
                $notified++;
            }
        }

        $this->info("Marked {$checkouts->count()} checkout(s) overdue; sent {$notified} notification(s).");

        return self::SUCCESS;
    }
}
