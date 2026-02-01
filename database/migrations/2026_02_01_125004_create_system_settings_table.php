<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('system_settings', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique();
        $table->text('value')->nullable();
        $table->timestamps();
    });
    
    // Insere valores padrão
    DB::table('system_settings')->insert([
        ['key' => 'registration_blocked', 'value' => '0'],
        ['key' => 'maintenance_mode', 'value' => '0'], // Opcional, pois o Laravel tem comando nativo
    ]);
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
