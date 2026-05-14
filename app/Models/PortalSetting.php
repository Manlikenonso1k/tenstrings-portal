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
        'allow_payment_reset',
    ];

    protected $casts = [
        'paystack_enabled' => 'boolean',
        'tgipay_enabled' => 'boolean',
        'allow_payment_reset' => 'boolean',
    ];

    public static function current(): self
    {
        return static::query()->first() ?? new self([
            'matric_pattern' => '{ycode}{seq:8}',
            'next_sequence' => 1,
            'paystack_enabled' => false,
            'tgipay_enabled' => true,
            'allow_payment_reset' => false,
        ]);
    }

    public function gatewayEnabled(string $gateway): bool
    {
        $gateway = strtolower($gateway);
        if ($gateway === 'paystack' || $gateway === 'paystack-titan' || $gateway === 'paystack_titan') {
            return (bool) $this->paystack_enabled;
        }
        if ($gateway === 'tgipay' || $gateway === 'tgi-pay' || $gateway === 'tgi_pay') {
            return (bool) $this->tgipay_enabled;
        }
        return false;
    }
}
