<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_audit_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_audit_id')->constrained('inventory_audits')->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->unsignedInteger('expected_quantity')->default(0);
            $table->unsignedInteger('counted_quantity')->nullable();
            $table->string('condition_found')->nullable();
            $table->boolean('is_missing')->default(false);
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->unique(['inventory_audit_id', 'inventory_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_audit_lines');
    }
};
