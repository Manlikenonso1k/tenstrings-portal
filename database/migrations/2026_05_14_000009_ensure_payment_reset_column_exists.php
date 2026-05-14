<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('portal_settings')) {
            if (!Schema::hasColumn('portal_settings', 'allow_payment_reset')) {
                Schema::table('portal_settings', function (Blueprint $table) {
                    $table->boolean('allow_payment_reset')->default(false)->after('tgipay_enabled');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('portal_settings') && Schema::hasColumn('portal_settings', 'allow_payment_reset')) {
            Schema::table('portal_settings', function (Blueprint $table) {
                $table->dropColumn('allow_payment_reset');
            });
        }
    }
};
