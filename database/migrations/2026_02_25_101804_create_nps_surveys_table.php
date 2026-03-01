<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nps_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->unique()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Cliente que avaliou
            $table->integer('score'); // 0 a 10 (NPS)
            $table->text('comment')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });

        // Adicionar coluna nps_score na tabela de tickets para facilitar a consulta rápida
        Schema::table('tickets', function (Blueprint $table) {
            $table->integer('nps_score')->nullable()->after('rating');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('nps_score');
        });
        Schema::dropIfExists('nps_surveys');
    }
};
