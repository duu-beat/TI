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
    // 1. Adiciona flag de nota interna nas mensagens
    Schema::table('ticket_messages', function (Blueprint $table) {
        $table->boolean('is_internal')->default(false)->after('message');
    });

    // 2. Adiciona avaliação nos chamados
    Schema::table('tickets', function (Blueprint $table) {
        $table->integer('rating')->nullable()->after('status'); // 1 a 5
        $table->text('rating_comment')->nullable()->after('rating');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
