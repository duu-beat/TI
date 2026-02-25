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
        Schema::create('technical_visits', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade'); // Técnico responsável
            $blueprint->dateTime('scheduled_at'); // Data e hora da visita
            $blueprint->string('status')->default('scheduled'); // scheduled, in_transit, in_service, completed, cancelled
            $blueprint->text('address'); // Endereço da visita
            $blueprint->text('notes')->nullable(); // Observações do técnico
            $blueprint->text('client_feedback')->nullable(); // Feedback do cliente após a visita
            $blueprint->dateTime('started_at')->nullable();
            $blueprint->dateTime('completed_at')->nullable();
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_visits');
    }
};
