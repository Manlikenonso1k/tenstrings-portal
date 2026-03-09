<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'selected_course_name')) {
                $table->string('selected_course_name')->nullable()->after('student_number');
            }

            if (! Schema::hasColumn('students', 'selected_course_code')) {
                $table->string('selected_course_code', 10)->nullable()->after('selected_course_name');
            }

            if (! Schema::hasColumn('students', 'avatar_url')) {
                $table->string('avatar_url')->nullable()->after('photo_path');
            }

            if (! Schema::hasColumn('students', 'birth_certificate_path')) {
                $table->string('birth_certificate_path')->nullable()->after('avatar_url');
            }

            if (! Schema::hasColumn('students', 'jamb_path')) {
                $table->string('jamb_path')->nullable()->after('birth_certificate_path');
            }

            if (! Schema::hasColumn('students', 'neco_path')) {
                $table->string('neco_path')->nullable()->after('jamb_path');
            }

            if (! Schema::hasColumn('students', 'waec_path')) {
                $table->string('waec_path')->nullable()->after('neco_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            foreach (['waec_path', 'neco_path', 'jamb_path', 'birth_certificate_path', 'avatar_url', 'selected_course_code', 'selected_course_name'] as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
