<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->boolean('is_luxury_branch')->default(false);
            $table->decimal('markup_percentage', 5, 2)->default(0.00);
            $table->timestamps();
        });

        // Seed the four known branches
        DB::table('branches')->insert([
            [
                'name'              => 'AJAH BRANCH',
                'slug'              => 'ajah-branch',
                'is_luxury_branch'  => false,
                'markup_percentage' => 0.00,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'AGEGE BRANCH',
                'slug'              => 'agege-branch',
                'is_luxury_branch'  => false,
                'markup_percentage' => 0.00,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'IKEJA BRANCH',
                'slug'              => 'ikeja-branch',
                'is_luxury_branch'  => false,
                'markup_percentage' => 0.00,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'FESTAC BRANCH',
                'slug'              => 'festac-branch',
                'is_luxury_branch'  => false,
                'markup_percentage' => 0.00,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
