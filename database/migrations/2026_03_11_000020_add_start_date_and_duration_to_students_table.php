<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'start_date')) {
                $table->date('start_date')->nullable()->after('date_of_birth');
            }

            if (! Schema::hasColumn('students', 'duration')) {
                $table->string('duration', 30)->nullable()->after('selected_course_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'start_date')) {
                $table->dropColumn('start_date');
            }

            if (Schema::hasColumn('students', 'duration')) {
                $table->dropColumn('duration');
            }
        });
    }
};
