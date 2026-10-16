<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_histories', function (Blueprint $table): void {
            $table->foreignId('old_branch_id')->nullable()->after('new_user_id')->constrained('branches')->nullOnDelete();
            $table->foreignId('new_branch_id')->nullable()->after('old_branch_id')->constrained('branches')->nullOnDelete();
            $table->foreignId('old_sector_id')->nullable()->after('new_branch_id')->constrained('sectors')->nullOnDelete();
            $table->foreignId('new_sector_id')->nullable()->after('old_sector_id')->constrained('sectors')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('asset_histories', function (Blueprint $table): void {
            $table->dropForeign(['new_sector_id']);
            $table->dropForeign(['old_sector_id']);
            $table->dropForeign(['new_branch_id']);
            $table->dropForeign(['old_branch_id']);
            $table->dropColumn(['new_sector_id', 'old_sector_id', 'new_branch_id', 'old_branch_id']);
        });
    }
};
