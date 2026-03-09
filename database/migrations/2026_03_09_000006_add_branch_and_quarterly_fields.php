<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'branch')) {
                $table->string('branch')->default('IKEJA BRANCH')->after('address');
            }
        });

        Schema::table('enrollments', function (Blueprint $table) {
            if (! Schema::hasColumn('enrollments', 'intake_month')) {
                $table->string('intake_month')->nullable()->after('enrollment_date');
            }
        });

        Schema::table('grades', function (Blueprint $table) {
            if (! Schema::hasColumn('grades', 'assessment_month')) {
                $table->string('assessment_month')->nullable()->after('assessment_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            if (Schema::hasColumn('grades', 'assessment_month')) {
                $table->dropColumn('assessment_month');
            }
        });

        Schema::table('enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('enrollments', 'intake_month')) {
                $table->dropColumn('intake_month');
            }
        });

        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'branch')) {
                $table->dropColumn('branch');
            }
        });
    }
};
