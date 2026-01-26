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
    Schema::create('ticket_attachments', function (Blueprint $table) {
        $table->id();
        // Vincula o anexo a uma mensagem específica
        $table->foreignId('ticket_message_id')->constrained()->cascadeOnDelete();
        $table->string('file_path'); // Onde o arquivo está salvo (ex: attachments/foto.png)
        $table->string('file_name'); // Nome original (ex: erro_tela.png)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_attachments');
    }
};
