<?php

namespace App\Enums;

enum ItemStatus: string
{
    case InUse = 'in_use';
    case InStorage = 'in_storage';
    case UnderRepair = 'under_repair';
    case Disposed = 'disposed';
    case Missing = 'missing';

    public function label(): string
    {
        return match ($this) {
            self::InUse => 'In Use',
            self::InStorage => 'In Storage',
            self::UnderRepair => 'Under Repair',
            self::Disposed => 'Disposed',
            self::Missing => 'Missing',
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
