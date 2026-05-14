<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portal_settings', function (Blueprint $table): void {
            $table->boolean('paystack_enabled')->default(false)->after('next_sequence');
            $table->boolean('tgipay_enabled')->default(true)->after('paystack_enabled');
        });

        DB::table('portal_settings')->update([
            'paystack_enabled' => false,
            'tgipay_enabled' => true,
        ]);
    }

    public function down(): void
    {
        Schema::table('portal_settings', function (Blueprint $table): void {
            $table->dropColumn(['paystack_enabled', 'tgipay_enabled']);
        });
    }
};