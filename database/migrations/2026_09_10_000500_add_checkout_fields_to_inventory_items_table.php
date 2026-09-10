<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remembers what an item's status was before it went out to an event, so
     * a full return can put it back rather than guessing `in_use`.
     */
    public function up(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            if (! Schema::hasColumn('inventory_items', 'status_before_checkout')) {
                $table->string('status_before_checkout')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            if (Schema::hasColumn('inventory_items', 'status_before_checkout')) {
                $table->dropColumn('status_before_checkout');
            }
        });
    }
};
