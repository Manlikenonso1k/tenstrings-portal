<?php

namespace App\Enums;

enum ItemStatus: string
{
    case InUse = 'in_use';
    case InStorage = 'in_storage';
    case UnderRepair = 'under_repair';
    case Disposed = 'disposed';
    case Missing = 'missing';
    case CheckedOut = 'checked_out';

    public function label(): string
    {
        return match ($this) {
            self::InUse => 'In Use',
            self::InStorage => 'In Storage',
            self::UnderRepair => 'Under Repair',
            self::Disposed => 'Disposed',
            self::Missing => 'Missing',
            self::CheckedOut => 'Checked out',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::InUse => 'success',
            self::InStorage => 'gray',
            self::UnderRepair => 'warning',
            self::Disposed => 'danger',
            self::Missing => 'danger',
            self::CheckedOut => 'warning',
        };
    }

    /**
     * Statuses that put an item on the CEO's exceptions list.
     *
     * @return list<string>
     */
    public static function needingAttention(): array
    {
        return [self::UnderRepair->value, self::Missing->value];
    }

    /**
     * Statuses that make an item ineligible to leave for an event.
     *
     * @return list<string>
     */
    public static function blockedFromCheckout(): array
    {
        return [self::UnderRepair->value, self::Disposed->value, self::Missing->value];
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case): array => [$case->value => $case->label()])
            ->all();
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
