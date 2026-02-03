<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Atribuição de Chamados (na tabela 'tickets')
        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                if (!Schema::hasColumn('tickets', 'assigned_to')) {
                    $table->foreignId('assigned_to')
                          ->nullable()
                          ->constrained('users')
                          ->nullOnDelete();
                }
            });
        }

        // 2. Notas Internas e Time Tracking (na tabela 'ticket_messages')
        if (Schema::hasTable('ticket_messages')) {
            Schema::table('ticket_messages', function (Blueprint $table) {
                if (!Schema::hasColumn('ticket_messages', 'is_internal')) {
                    $table->boolean('is_internal')->default(false);
                }
                
                if (!Schema::hasColumn('ticket_messages', 'time_spent')) {
                    $table->integer('time_spent')->default(0)->comment('Tempo em minutos');
                }
            });
        }

        // 3. Respostas Prontas (Tabela Nova)
        if (!Schema::hasTable('canned_responses')) {
            Schema::create('canned_responses', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        // Remove a tabela de respostas prontas
        Schema::dropIfExists('canned_responses');
        
        // Remove colunas extras de ticket_messages
        if (Schema::hasTable('ticket_messages')) {
            Schema::table('ticket_messages', function (Blueprint $table) {
                if (Schema::hasColumn('ticket_messages', 'is_internal')) {
                    $table->dropColumn('is_internal');
                }
                if (Schema::hasColumn('ticket_messages', 'time_spent')) {
                    $table->dropColumn('time_spent');
                }
            });
        }

        // Remove a coluna de atribuição de tickets
        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                if (Schema::hasColumn('tickets', 'assigned_to')) {
                    $table->dropForeign(['assigned_to']);
                    $table->dropColumn('assigned_to');
                }
            });
        }
    }
};