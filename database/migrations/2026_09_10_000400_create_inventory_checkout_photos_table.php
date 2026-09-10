<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The project has no Media Library, so before/after evidence photos live in
     * their own table with a stage of `out` or `in`.
     */
    public function up(): void
    {
        Schema::create('inventory_checkout_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_checkout_item_id')->constrained('inventory_checkout_items')->cascadeOnDelete();
            $table->string('stage');
            $table->string('path');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['inventory_checkout_item_id', 'stage'], 'inv_co_photo_line_stage_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_checkout_photos');
    }
};
