<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'fees_paid')) {
                $table->decimal('fees_paid', 12, 2)->default(0)->after('duration');
            }

            if (! Schema::hasColumn('students', 'balance_due')) {
                $table->decimal('balance_due', 12, 2)->default(0)->after('fees_paid');
            }

            if (! Schema::hasColumn('students', 'hostel_fee')) {
                $table->decimal('hostel_fee', 12, 2)->default(0)->after('balance_due');
            }

            if (! Schema::hasColumn('students', 'total_balance')) {
                $table->decimal('total_balance', 12, 2)->default(0)->after('hostel_fee');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            foreach (['total_balance', 'hostel_fee', 'balance_due', 'fees_paid'] as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
