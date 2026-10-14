<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table): void {
            $table->foreignId('branch_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->foreignId('sector_id')->nullable()->after('branch_id')->constrained()->nullOnDelete();
            $table->index(['branch_id', 'sector_id']);
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table): void {
            $table->dropIndex(['branch_id', 'sector_id']);
            $table->dropForeign(['sector_id']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['sector_id', 'branch_id']);
        });
    }
};
