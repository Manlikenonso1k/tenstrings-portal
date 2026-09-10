<?php

namespace App\Enums;

enum ReturnStatus: string
{
    case ReturnedOk = 'returned_ok';
    case ReturnedDamaged = 'returned_damaged';
    case Missing = 'missing';

    public function label(): string
    {
        return match ($this) {
            self::ReturnedOk => 'Returned OK',
            self::ReturnedDamaged => 'Returned damaged',
            self::Missing => 'Missing',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ReturnedOk => 'success',
            self::ReturnedDamaged => 'warning',
            self::Missing => 'danger',
        };
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case): array => [$case->value => $case->label()])
            ->all();
    }
}
