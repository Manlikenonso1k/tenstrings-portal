<?php

namespace App\Enums;

enum RoomType: string
{
    case Classroom = 'classroom';
    case PracticeStudio = 'practice_studio';
    case Office = 'office';
    case Reception = 'reception';
    case Store = 'store';
    case Library = 'library';
    case Hall = 'hall';
    case Restroom = 'restroom';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Classroom => 'Classroom',
            self::PracticeStudio => 'Practice Studio',
            self::Office => 'Office',
            self::Reception => 'Reception',
            self::Store => 'Store',
            self::Library => 'Library',
            self::Hall => 'Hall',
            self::Restroom => 'Restroom',
            self::Other => 'Other',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Classroom, self::PracticeStudio => 'info',
            self::Office, self::Reception => 'primary',
            self::Store => 'warning',
            self::Library, self::Hall => 'success',
            default => 'gray',
        };
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
