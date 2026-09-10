<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * An event-level checkout: one concert covers many instruments, and the
     * lines in inventory_checkout_items come back independently.
     */
    public function up(): void
    {
        Schema::create('inventory_checkouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->string('reference')->unique();

            $table->string('event_name');
            $table->string('event_venue')->nullable();
            $table->date('event_date');

            // The person carrying the items out may not be a portal user.
            $table->string('responsible_person_name');
            $table->string('responsible_person_phone')->nullable();

            $table->timestamp('expected_return_at');
            $table->foreignId('checked_out_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('checked_out_at')->nullable();

            $table->string('status')->default('out');
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['branch_id', 'status']);
            $table->index('expected_return_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_checkouts');
    }
};
