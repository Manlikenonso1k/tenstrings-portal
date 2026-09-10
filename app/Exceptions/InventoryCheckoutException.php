<?php

namespace App\Exceptions;

use RuntimeException;

class InventoryCheckoutException extends RuntimeException
{
    public static function notEnoughPhotos(string $itemName, int $given, int $required): self
    {
        return new self("{$itemName}: {$required} photos are required, {$given} given. Every check-out and every return needs photographic evidence.");
    }

    public static function itemBlocked(string $itemName, string $status): self
    {
        return new self("{$itemName} cannot leave: its status is \"{$status}\".");
    }

    public static function notEnoughAvailable(string $itemName, int $requested, int $available): self
    {
        return new self("{$itemName}: only {$available} available, {$requested} requested.");
    }

    public static function noLines(): self
    {
        return new self('A checkout needs at least one item.');
    }

    public static function returnTooLarge(string $itemName, int $requested, int $outstanding): self
    {
        return new self("{$itemName}: only {$outstanding} still out, {$requested} being returned.");
    }

    public static function alreadyClosed(string $reference): self
    {
        return new self("Checkout {$reference} is already fully returned.");
    }

    public static function crossBranch(string $itemName): self
    {
        return new self("{$itemName} belongs to a different branch than this checkout.");
    }
}
