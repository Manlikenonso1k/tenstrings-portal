<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('portal_settings')) {
            // Ensure TGIPAY is enabled for existing portal settings and Paystack remains disabled by default
            DB::table('portal_settings')->update([
                'tgipay_enabled' => true,
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('portal_settings')) {
            // Revert TGIPAY to false in rollback (safe fallback)
            DB::table('portal_settings')->update([
                'tgipay_enabled' => false,
            ]);
        }
    }
};
