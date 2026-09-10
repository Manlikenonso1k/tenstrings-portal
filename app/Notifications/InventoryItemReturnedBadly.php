<?php

namespace App\Notifications;

use App\Enums\ReturnStatus;
use App\Models\InventoryCheckout;
use App\Models\InventoryItem;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InventoryItemReturnedBadly extends Notification
{
    use Queueable;

    public function __construct(
        protected InventoryCheckout $checkout,
        protected InventoryItem $item,
        protected string $returnStatus,
    ) {
    }

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        $status = ReturnStatus::tryFrom($this->returnStatus);
        $label = $status?->label() ?? $this->returnStatus;

        return FilamentNotification::make()
            ->title($this->item->name . ' came back ' . strtolower($label))
            ->body($this->checkout->reference . ' — ' . $this->checkout->event_name . '. Asset tag ' . ($this->item->asset_tag ?: 'none') . '.')
            ->icon('heroicon-o-exclamation-triangle')
            ->color($status === ReturnStatus::Missing ? 'danger' : 'warning')
            ->getDatabaseMessage();
    }
}
