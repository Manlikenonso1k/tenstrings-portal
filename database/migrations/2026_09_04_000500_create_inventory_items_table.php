<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();

            // branch_id is denormalised even though the room implies it: it
            // makes branch scoping and reporting cheap, and it survives the
            // room being deleted. InventoryItemObserver keeps it in sync.
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('inventory_room_id')->nullable()->constrained('inventory_rooms')->nullOnDelete();
            $table->foreignId('inventory_category_id')->constrained('inventory_categories')->cascadeOnDelete();

            $table->string('name');
            $table->string('asset_tag')->nullable()->unique();
            $table->string('serial_number')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->string('unit')->default('unit');
            $table->string('condition')->default('good');
            $table->string('status')->default('in_use');
            $table->date('acquisition_date')->nullable();
            $table->decimal('purchase_cost', 12, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->string('photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('last_verified_at')->nullable();

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['branch_id', 'inventory_room_id']);
            $table->index('condition');
            $table->index('status');
            $table->index('serial_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
