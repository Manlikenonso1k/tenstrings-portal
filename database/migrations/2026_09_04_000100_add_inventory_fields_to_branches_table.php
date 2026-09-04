<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The branches table already exists (name, slug, luxury pricing). The
     * inventory module needs a short code for asset tags, plus the address and
     * active flag the spec asks for.
     */
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            if (! Schema::hasColumn('branches', 'code')) {
                $table->string('code', 8)->nullable()->after('slug');
            }

            if (! Schema::hasColumn('branches', 'address')) {
                $table->string('address')->nullable()->after('code');
            }

            if (! Schema::hasColumn('branches', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('address');
            }
        });

        // Backfill codes for the four seeded branches, then anything else by
        // the first three letters of its name.
        $known = [
            'ajah-branch' => 'AJA',
            'agege-branch' => 'AGE',
            'ikeja-branch' => 'IKJ',
            'festac-branch' => 'FES',
        ];

        foreach (DB::table('branches')->get(['id', 'name', 'slug', 'code']) as $branch) {
            if (filled($branch->code ?? null)) {
                continue;
            }

            $code = $known[$branch->slug]
                ?? strtoupper(substr(preg_replace('/[^A-Za-z]/', '', (string) $branch->name) ?: 'BRN', 0, 3));

            DB::table('branches')->where('id', $branch->id)->update(['code' => $code]);
        }

        Schema::table('branches', function (Blueprint $table) {
            $table->unique('code');
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            if (Schema::hasColumn('branches', 'code')) {
                $table->dropUnique(['code']);
                $table->dropColumn('code');
            }

            if (Schema::hasColumn('branches', 'address')) {
                $table->dropColumn('address');
            }

            if (Schema::hasColumn('branches', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
