<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $header) {
            $header->id();
            $header->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $header->string('name'); // Ex: Notebook Dell Latitude 3420
            $header->string('tag')->unique(); // Patrimônio: TI-001
            $header->string('serial_number')->nullable();
            $header->string('type'); // Laptop, Desktop, Monitor, Impressora, etc.
            $header->string('model')->nullable();
            $header->string('brand')->nullable();
            $header->date('purchase_date')->nullable();
            $header->date('warranty_expiration')->nullable();
            $header->enum('status', ['active', 'maintenance', 'retired', 'lost'])->default('active');
            $header->text('notes')->nullable();
            $header->timestamps();
        });

        // Adicionar coluna asset_id na tabela de tickets para vincular um chamado a um equipamento
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('asset_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['asset_id']);
            $table->dropColumn('asset_id');
        });
        Schema::dropIfExists('assets');
    }
};
