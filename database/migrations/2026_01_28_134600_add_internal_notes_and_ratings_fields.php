<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Adiciona flag de nota interna nas mensagens (com verificação)
        if (Schema::hasTable('ticket_messages')) {
            Schema::table('ticket_messages', function (Blueprint $table) {
                if (!Schema::hasColumn('ticket_messages', 'is_internal')) {
                    $table->boolean('is_internal')->default(false)->after('message');
                }
            });
        }

        // 2. Adiciona avaliação nos chamados (com verificação)
        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                if (!Schema::hasColumn('tickets', 'rating')) {
                    $table->integer('rating')->nullable()->after('status'); // 1 a 5
                }
                
                if (!Schema::hasColumn('tickets', 'rating_comment')) {
                    $table->text('rating_comment')->nullable()->after('rating');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_messages', function (Blueprint $table) {
            if (Schema::hasColumn('ticket_messages', 'is_internal')) {
                $table->dropColumn('is_internal');
            }
        });

        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'rating')) {
                $table->dropColumn(['rating', 'rating_comment']);
            }
        });
    }
};