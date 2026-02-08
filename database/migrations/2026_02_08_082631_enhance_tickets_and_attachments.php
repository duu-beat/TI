<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Adicionar campos de SLA e métricas aos tickets
        Schema::table('tickets', function (Blueprint $table) {
            $table->timestamp('sla_due_at')->nullable()->after('is_escalated');
            $table->timestamp('first_response_at')->nullable()->after('sla_due_at');
            $table->timestamp('resolved_at')->nullable()->after('first_response_at');
            $table->integer('response_time_minutes')->nullable()->after('resolved_at');
            $table->integer('resolution_time_minutes')->nullable()->after('response_time_minutes');
        });

        // Melhorias na tabela de anexos
        Schema::table('ticket_attachments', function (Blueprint $table) {
            $table->string('mime_type')->nullable()->after('file_name');
            $table->integer('size')->nullable()->after('mime_type'); // em bytes
            $table->string('disk')->default('public')->after('size');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'sla_due_at',
                'first_response_at',
                'resolved_at',
                'response_time_minutes',
                'resolution_time_minutes'
            ]);
        });

        Schema::table('ticket_attachments', function (Blueprint $table) {
            $table->dropColumn(['mime_type', 'size', 'disk']);
        });
    }
};
