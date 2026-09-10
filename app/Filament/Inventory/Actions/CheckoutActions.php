<?php

namespace App\Filament\Inventory\Actions;

use App\Enums\CheckoutStatus;
use App\Exceptions\InventoryCheckoutException;
use App\Models\InventoryCheckout;
use App\Models\InventoryCheckoutItem;
use App\Models\InventoryItem;
use App\Services\Inventory\InventoryCheckoutService;
use Filament\Notifications\Notification;
use Filament\Tables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Every entry point into the check-out and return flows. All of them call
 * InventoryCheckoutService, which owns the rules — these only gather input.
 */
class CheckoutActions
{
    /**
     * Row action on the room page and All items: check this one item out.
     */
    public static function checkoutItem(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('checkout')
            ->label('Check out for event')
            ->icon('heroicon-o-arrow-up-on-square')
            ->color('warning')
            ->modalWidth('3xl')
            ->modalSubmitActionLabel('Check out items')
            ->visible(fn (InventoryItem $record): bool => static::canCheckout($record))
            ->mountUsing(function (\Filament\Forms\Form $form, InventoryItem $record): void {
                $form->fill([
                    'branch_id' => $record->branch_id,
                    'event_date' => now(),
                    'expected_return_at' => now()->addDay(),
                    'lines' => [[
                        'inventory_item_id' => $record->getKey(),
                        'quantity' => 1,
                        'condition_out' => $record->condition?->value,
                        'photos_out' => [],
                    ]],
                ]);
            })
            ->steps(fn (InventoryItem $record): array => CheckoutFormSchema::checkoutSteps((int) $record->branch_id))
            ->action(fn (array $data) => static::runCheckout($data));
    }

    /**
     * Bulk action on All items: one event, several instruments.
     */
    public static function checkoutSelected(): Tables\Actions\BulkAction
    {
        return Tables\Actions\BulkAction::make('checkoutSelected')
            ->label('Check out for event')
            ->icon('heroicon-o-arrow-up-on-square')
            ->color('warning')
            ->modalWidth('3xl')
            ->modalSubmitActionLabel('Check out items')
            ->deselectRecordsAfterCompletion()
            ->visible(fn (): bool => Auth::user()?->can('inventory_checkout.create') ?? false)
            ->mountUsing(function (\Filament\Forms\Form $form, Collection $records): void {
                $eligible = $records->filter(fn (InventoryItem $item): bool => static::canCheckout($item));

                $form->fill([
                    'branch_id' => $eligible->first()?->branch_id ?? Auth::user()?->branch_id,
                    'event_date' => now(),
                    'expected_return_at' => now()->addDay(),
                    'lines' => $eligible->map(fn (InventoryItem $item): array => [
                        'inventory_item_id' => $item->getKey(),
                        'quantity' => 1,
                        'condition_out' => $item->condition?->value,
                        'photos_out' => [],
                    ])->values()->all(),
                ]);
            })
            ->steps(CheckoutFormSchema::checkoutSteps())
            ->action(fn (array $data) => static::runCheckout($data));
    }

    /**
     * "New checkout" from the Event checkouts page.
     */
    public static function newCheckout(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('newCheckout')
            ->label('New checkout')
            ->icon('heroicon-o-plus')
            ->modalWidth('3xl')
            ->modalSubmitActionLabel('Check out items')
            ->visible(fn (): bool => Auth::user()?->can('inventory_checkout.create') ?? false)
            ->steps(CheckoutFormSchema::checkoutSteps())
            ->action(fn (array $data) => static::runCheckout($data));
    }

    /**
     * "Return items" on a checkout row or its view page.
     */
    public static function returnCheckout(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('returnItems')
            ->label('Return items')
            ->icon('heroicon-o-arrow-down-on-square')
            ->color('success')
            ->modalWidth('3xl')
            ->modalSubmitActionLabel('Confirm return')
            ->visible(fn (InventoryCheckout $record): bool => Auth::user()?->can('return', $record) ?? false)
            ->steps(fn (InventoryCheckout $record): array => CheckoutFormSchema::returnSteps($record))
            ->action(fn (InventoryCheckout $record, array $data) => static::runReturn($record, $data));
    }

    /**
     * Header version of the same thing, for the checkout view page.
     */
    public static function returnCheckoutHeader(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('returnItems')
            ->label('Return items')
            ->icon('heroicon-o-arrow-down-on-square')
            ->color('success')
            ->modalWidth('3xl')
            ->modalSubmitActionLabel('Confirm return')
            ->visible(fn (InventoryCheckout $record): bool => Auth::user()?->can('return', $record) ?? false)
            ->steps(fn (InventoryCheckout $record): array => CheckoutFormSchema::returnSteps($record))
            ->action(fn (InventoryCheckout $record, array $data) => static::runReturn($record, $data));
    }

    /**
     * Row action on an item that is currently out: jump straight to returning
     * it on whichever open checkout holds it.
     */
    public static function returnItem(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('returnItem')
            ->label('Return')
            ->icon('heroicon-o-arrow-down-on-square')
            ->color('success')
            ->modalWidth('3xl')
            ->modalSubmitActionLabel('Confirm return')
            ->visible(fn (InventoryItem $record): bool => static::openCheckoutFor($record) !== null
                && (Auth::user()?->can('inventory_checkout.return') ?? false))
            ->steps(function (InventoryItem $record): array {
                $checkout = static::openCheckoutFor($record);

                return $checkout ? CheckoutFormSchema::returnSteps($checkout) : [];
            })
            ->action(function (InventoryItem $record, array $data) {
                $checkout = static::openCheckoutFor($record);

                if (! $checkout instanceof InventoryCheckout) {
                    Notification::make()->danger()->title('This item is not out on any open checkout.')->send();

                    return;
                }

                static::runReturn($checkout, $data);
            });
    }

    public static function canCheckout(InventoryItem $item): bool
    {
        return (Auth::user()?->can('inventory_checkout.create') ?? false)
            && ! $item->isBlockedFromCheckout()
            && $item->availableQuantity() > 0;
    }

    /**
     * The most recent open checkout holding this item.
     */
    public static function openCheckoutFor(InventoryItem $item): ?InventoryCheckout
    {
        $line = InventoryCheckoutItem::query()
            ->where('inventory_item_id', $item->getKey())
            ->whereColumn('returned_quantity', '<', 'quantity')
            ->whereHas('checkout', fn ($query) => $query->whereIn('status', CheckoutStatus::open()))
            ->latest('id')
            ->first();

        return $line?->checkout;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected static function runCheckout(array $data): void
    {
        try {
            $checkout = app(InventoryCheckoutService::class)->checkout(
                CheckoutFormSchema::toCheckoutPayload($data),
                Auth::user(),
            );
        } catch (InventoryCheckoutException $exception) {
            Notification::make()
                ->danger()
                ->title('Check-out refused')
                ->body($exception->getMessage())
                ->persistent()
                ->send();

            return;
        }

        Notification::make()
            ->success()
            ->title('Checked out as ' . $checkout->reference)
            ->body($checkout->lines->count() . ' item line(s) released for ' . $checkout->event_name . '.')
            ->send();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected static function runReturn(InventoryCheckout $checkout, array $data): void
    {
        try {
            $checkout = app(InventoryCheckoutService::class)->returnItems(
                $checkout,
                CheckoutFormSchema::toReturnPayload($data),
                Auth::user(),
            );
        } catch (InventoryCheckoutException $exception) {
            Notification::make()
                ->danger()
                ->title('Return refused')
                ->body($exception->getMessage())
                ->persistent()
                ->send();

            return;
        }

        Notification::make()
            ->success()
            ->title('Return recorded')
            ->body($checkout->reference . ' is now ' . strtolower($checkout->status->label()) . '.')
            ->send();
    }
}
