<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->uuid('qr_token')->nullable()->unique()->after('tag');
        });

        DB::table('assets')->orderBy('id')->each(function (object $asset): void {
            DB::table('assets')
                ->where('id', $asset->id)
                ->update(['qr_token' => (string) Str::uuid()]);
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropUnique(['qr_token']);
            $table->dropColumn('qr_token');
        });
    }
};
