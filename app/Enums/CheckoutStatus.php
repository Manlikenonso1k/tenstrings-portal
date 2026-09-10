<?php

namespace App\Enums;

enum CheckoutStatus: string
{
    case Out = 'out';
    case PartiallyReturned = 'partially_returned';
    case Returned = 'returned';
    case Overdue = 'overdue';

    public function label(): string
    {
        return match ($this) {
            self::Out => 'Out',
            self::PartiallyReturned => 'Partially returned',
            self::Returned => 'Returned',
            self::Overdue => 'Overdue',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Out => 'warning',
            self::PartiallyReturned => 'info',
            self::Returned => 'success',
            self::Overdue => 'danger',
        };
    }

    /**
     * Statuses that still have items in someone else's hands.
     *
     * @return list<string>
     */
    public static function open(): array
    {
        return [self::Out->value, self::PartiallyReturned->value, self::Overdue->value];
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case): array => [$case->value => $case->label()])
            ->all();
    }
}
