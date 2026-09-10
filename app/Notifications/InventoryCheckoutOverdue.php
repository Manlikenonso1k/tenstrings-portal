<?php

namespace App\Notifications;

use App\Models\InventoryCheckout;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InventoryCheckoutOverdue extends Notification
{
    use Queueable;

    public function __construct(protected InventoryCheckout $checkout)
    {
    }

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Checkout ' . $this->checkout->reference . ' is overdue')
            ->body($this->checkout->event_name . ' was due back ' . $this->checkout->expected_return_at?->diffForHumans() . '. Held by ' . $this->checkout->responsible_person_name . '.')
            ->icon('heroicon-o-clock')
            ->color('danger')
            ->getDatabaseMessage();
    }
}
