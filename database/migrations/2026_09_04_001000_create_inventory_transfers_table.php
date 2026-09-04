<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->string('destination');
            $table->enum('type', ['internal', 'external_event', 'return']);
            $table->date('date');
            $table->timestamps();
            $table->index(['item_id', 'date'], 'inv_transfer_item_date_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transfers');
    }
};