<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_contracts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('number', 100);
            $table->string('type', 30)->default('maintenance');
            $table->date('starts_at');
            $table->date('expires_at');
            $table->decimal('amount', 12, 2)->nullable();
            $table->boolean('auto_renew')->default(false);
            $table->string('invoice_number', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['asset_id', 'expires_at']);
            $table->index(['supplier_id', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_contracts');
    }
};
