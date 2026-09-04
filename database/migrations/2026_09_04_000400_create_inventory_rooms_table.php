<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 32);
            $table->string('floor')->nullable();
            $table->string('room_type')->default('other');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_audited_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['branch_id', 'code']);
            $table->index('room_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_rooms');
    }
};
