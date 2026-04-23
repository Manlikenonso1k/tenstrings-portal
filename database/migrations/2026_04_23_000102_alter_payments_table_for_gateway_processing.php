<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->after('user_id')->constrained('invoices')->nullOnDelete();
            $table->string('gateway')->nullable()->after('invoice_id');
            $table->string('reference')->nullable()->after('gateway');
            $table->decimal('amount', 12, 2)->nullable()->after('reference');
            $table->enum('status', ['pending', 'processing', 'success', 'failed'])->default('pending')->after('amount');
            $table->json('gateway_response')->nullable()->after('status');
            $table->json('metadata')->nullable()->after('gateway_response');
            $table->timestamp('processed_at')->nullable()->after('metadata');

            $table->unique('reference');
            $table->index(['gateway', 'status']);
            $table->index('user_id');
            $table->index('invoice_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['gateway', 'status']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['invoice_id']);
            $table->dropUnique(['reference']);

            $table->dropConstrainedForeignId('invoice_id');
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn([
                'gateway',
                'reference',
                'amount',
                'status',
                'gateway_response',
                'metadata',
                'processed_at',
            ]);
        });
    }
};
