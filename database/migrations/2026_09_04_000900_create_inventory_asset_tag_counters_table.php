<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One counter row per branch + category. AssetTagGenerator takes a
     * lockForUpdate() on the row inside a transaction, so two officers saving
     * at the same moment cannot draw the same sequence number.
     */
    public function up(): void
    {
        Schema::create('inventory_asset_tag_counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('inventory_category_id')->constrained('inventory_categories')->cascadeOnDelete();
            $table->unsignedInteger('next_sequence')->default(1);
            $table->timestamps();

            $table->unique(['branch_id', 'inventory_category_id'], 'inventory_tag_counter_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_asset_tag_counters');
    }
};
