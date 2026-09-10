<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_checkout_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_checkout_id')->constrained('inventory_checkouts')->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->unsignedInteger('quantity');

            // condition_out / condition_in reuse App\Enums\ItemCondition values.
            $table->string('condition_out');
            $table->text('notes_out')->nullable();

            $table->string('condition_in')->nullable();
            $table->text('notes_in')->nullable();
            $table->unsignedInteger('returned_quantity')->default(0);
            $table->timestamp('returned_at')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('return_status')->nullable();

            $table->timestamps();

            $table->index(['inventory_checkout_id', 'inventory_item_id'], 'inv_co_item_checkout_item_idx');
            $table->index('inventory_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_checkout_items');
    }
};
