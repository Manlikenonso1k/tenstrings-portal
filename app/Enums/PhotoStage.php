<?php

namespace App\Enums;

enum PhotoStage: string
{
    case Out = 'out';
    case In = 'in';

    public function label(): string
    {
        return match ($this) {
            self::Out => 'Before (check-out)',
            self::In => 'After (return)',
        };
    }
}
