<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ticket_attachments') && ! Schema::hasColumn('ticket_attachments', 'thumbnail_path')) {
            Schema::table('ticket_attachments', function (Blueprint $table): void {
                $table->string('thumbnail_path')->nullable()->after('file_path');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ticket_attachments') && Schema::hasColumn('ticket_attachments', 'thumbnail_path')) {
            Schema::table('ticket_attachments', function (Blueprint $table): void {
                $table->dropColumn('thumbnail_path');
            });
        }
    }
};
