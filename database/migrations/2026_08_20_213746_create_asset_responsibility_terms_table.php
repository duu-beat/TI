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
        Schema::create('asset_responsibility_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recipient_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('issued_by')->constrained('users')->restrictOnDelete();
            $table->enum('type', ['delivery', 'return']);
            $table->enum('status', ['pending', 'signed', 'cancelled'])->default('pending');
            $table->text('terms_text');
            $table->string('signature_path')->nullable();
            $table->char('signature_hash', 64)->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->string('signed_ip', 45)->nullable();
            $table->string('signed_user_agent', 1000)->nullable();
            $table->timestamps();

            $table->index(['asset_id', 'status']);
            $table->index(['recipient_id', 'status']);
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_responsibility_terms');
    }
};
