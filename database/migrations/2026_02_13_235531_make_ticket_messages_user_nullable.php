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
        Schema::table('ticket_messages', function (Blueprint $table) {
            // Permite que a coluna user_id aceite NULL (para a IA)
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_messages', function (Blueprint $table) {
            // Reverte para não aceitar nulo (caso precise voltar atrás)
            // Nota: Isso pode dar erro se já existirem mensagens da IA no banco
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};