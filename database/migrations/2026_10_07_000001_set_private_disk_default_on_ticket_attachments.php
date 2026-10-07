<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ticket_attachments') || ! Schema::hasColumn('ticket_attachments', 'disk')) {
            return;
        }

        // Não altera registros existentes: arquivos antigos podem continuar no disco
        // em que foram armazenados. Apenas corrige valores sem disco definido.
        DB::table('ticket_attachments')
            ->whereNull('disk')
            ->update(['disk' => 'local']);

        Schema::table('ticket_attachments', function (Blueprint $table): void {
            $table->string('disk')->default('local')->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('ticket_attachments') || ! Schema::hasColumn('ticket_attachments', 'disk')) {
            return;
        }

        Schema::table('ticket_attachments', function (Blueprint $table): void {
            $table->string('disk')->default('public')->change();
        });
    }
};
