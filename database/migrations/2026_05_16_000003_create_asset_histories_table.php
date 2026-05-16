<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_histories', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('asset_id')->constrained()->onDelete('cascade');
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade'); // Quem realizou a ação
            $blueprint->string('action'); // create, update, delete, transfer
            $blueprint->text('description')->nullable();
            $blueprint->string('old_status')->nullable();
            $blueprint->string('new_status')->nullable();
            $blueprint->foreignId('old_user_id')->nullable()->constrained('users');
            $blueprint->foreignId('new_user_id')->nullable()->constrained('users');
            $blueprint->longText('signature')->nullable(); // Base64 da assinatura digital
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_histories');
    }
};
