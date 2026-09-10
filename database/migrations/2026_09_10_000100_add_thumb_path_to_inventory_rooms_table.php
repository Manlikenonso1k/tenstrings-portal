<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_rooms', function (Blueprint $table) {
            if (! Schema::hasColumn('inventory_rooms', 'image_thumb_path')) {
                $table->string('image_thumb_path')->nullable()->after('image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inventory_rooms', function (Blueprint $table) {
            if (Schema::hasColumn('inventory_rooms', 'image_thumb_path')) {
                $table->dropColumn('image_thumb_path');
            }
        });
    }
};
