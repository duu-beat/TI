<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modelos de Checklist por Categoria
        Schema::create('checklist_templates', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // Categoria do chamado (ex: Hardware, Rede)
            $table->string('title'); // Ex: Checklist de Configuração de E-mail
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Itens do Modelo de Checklist
        Schema::create('checklist_template_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('checklist_templates')->onDelete('cascade');
            $table->string('task'); // Ex: Configurar servidor SMTP
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Itens de Checklist vinculados a um Ticket específico (quando o técnico está atendendo)
        Schema::create('ticket_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->string('task');
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_checklists');
        Schema::dropIfExists('checklist_template_items');
        Schema::dropIfExists('checklist_templates');
    }
};
