<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_shortcode_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('course_name')->unique();
            $table->string('short_code', 10);
            $table->timestamps();
        });

        $defaults = [
            ['course_name' => 'Advance Diploma in Music Performance - 18 months', 'short_code' => 'ADMP'],
            ['course_name' => 'Advance Diploma in Music Production - 18 months', 'short_code' => 'ADPD'],
            ['course_name' => 'Diploma in Music Performance - 1 year', 'short_code' => 'DMP'],
            ['course_name' => 'Diploma in Music Production - 1 year', 'short_code' => 'DPD'],
            ['course_name' => 'Certificate in Music Performance - 6 months', 'short_code' => 'CMP6'],
            ['course_name' => 'Certificate in Music Production - 6 months', 'short_code' => 'CPD6'],
            ['course_name' => 'Certificate in Gospel Music Performance - 3 months', 'short_code' => 'CGP3'],
            ['course_name' => 'Certificate in Gospel Music Performance - 6 months', 'short_code' => 'CGP6'],
            ['course_name' => 'Diploma in Gospel Music Performance - 1 year', 'short_code' => 'DGMP'],
            ['course_name' => 'Certificate in Songwriting - 3/6 months', 'short_code' => 'CSW'],
            ['course_name' => 'Certificate in Piano - 3/6 months', 'short_code' => 'CPN'],
            ['course_name' => 'Certificate in Music Business - 3/6 months', 'short_code' => 'CMB'],
            ['course_name' => 'Certificate in Guitar - 3/6 months', 'short_code' => 'CGT'],
            ['course_name' => 'Certificate in Drums - 3/6 months', 'short_code' => 'CDR'],
            ['course_name' => 'Certificate in Voice - 3/6 months', 'short_code' => 'CVO'],
        ];

        foreach ($defaults as $row) {
            DB::table('course_shortcode_mappings')->insert([
                ...$row,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('course_shortcode_mappings');
    }
};
