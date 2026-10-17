<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('document', 30)->nullable()->unique();
            $table->string('email')->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('website')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['is_active', 'name']);
        });

        Schema::table('assets', function (Blueprint $table): void {
            $table->foreignId('supplier_id')->nullable()->after('sector_id')->constrained('suppliers')->nullOnDelete();
            $table->index('supplier_id');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table): void {
            $table->dropForeign(['supplier_id']);
            $table->dropIndex(['supplier_id']);
            $table->dropColumn('supplier_id');
        });

        Schema::dropIfExists('suppliers');
    }
};
