<?php

namespace App\Enums;

enum ItemCondition: string
{
    case New = 'new';
    case Good = 'good';
    case Fair = 'fair';
    case Poor = 'poor';
    case Damaged = 'damaged';
    case NeedsRepair = 'needs_repair';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Good => 'Good',
            self::Fair => 'Fair',
            self::Poor => 'Poor',
            self::Damaged => 'Damaged',
            self::NeedsRepair => 'Needs Repair',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::New, self::Good => 'success',
            self::Fair => 'info',
            self::Poor => 'warning',
            self::Damaged, self::NeedsRepair => 'danger',
        };
    }

    /**
     * Conditions that put an item on the CEO's exceptions list.
     *
     * @return list<string>
     */
    public static function needingAttention(): array
    {
        return [self::Poor->value, self::Damaged->value, self::NeedsRepair->value];
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
