<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ticket_messages') || ! Schema::hasColumn('ticket_messages', 'time_spent')) {
            return;
        }

        DB::table('ticket_messages')
            ->whereNull('time_spent')
            ->update(['time_spent' => 0]);

        Schema::table('ticket_messages', function (Blueprint $table): void {
            $table->integer('time_spent')->default(0)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('ticket_messages') || ! Schema::hasColumn('ticket_messages', 'time_spent')) {
            return;
        }

        Schema::table('ticket_messages', function (Blueprint $table): void {
            $table->integer('time_spent')->default(0)->nullable()->change();
        });
    }
};
