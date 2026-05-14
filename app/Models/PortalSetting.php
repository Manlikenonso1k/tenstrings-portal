<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortalSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'matric_pattern',
        'next_sequence',
        'paystack_enabled',
        'tgipay_enabled',
    ];

    protected $casts = [
        'paystack_enabled' => 'boolean',
        'tgipay_enabled' => 'boolean',
    ];

    public static function current(): self
    {
        return static::query()->first() ?? new self([
            'matric_pattern' => '{ycode}{seq:8}',
            'next_sequence' => 1,
            'paystack_enabled' => false,
            'tgipay_enabled' => true,
        ]);
    }

    public function gatewayEnabled(string $gateway): bool
    {
        return match (strtolower($gateway)) {
            'paystack', 'paystack-titan', 'paystack_titan' => (bool) $this->paystack_enabled,
            'tgipay', 'tgi-pay', 'tgi_pay' => (bool) $this->tgipay_enabled,
            default => false,
        };
    }
}
