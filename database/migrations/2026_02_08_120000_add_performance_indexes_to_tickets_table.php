<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'tickets_status_created_at_idx');
            $table->index(['priority', 'status'], 'tickets_priority_status_idx');
            $table->index('assigned_to', 'tickets_assigned_to_idx');
            $table->index('category', 'tickets_category_idx');
            $table->index('created_at', 'tickets_created_at_idx');
            $table->index('sla_due_at', 'tickets_sla_due_at_idx');
            $table->index(['is_escalated', 'status'], 'tickets_is_escalated_status_idx');
            $table->index(['user_id', 'created_at'], 'tickets_user_id_created_at_idx');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex('tickets_status_created_at_idx');
            $table->dropIndex('tickets_priority_status_idx');
            $table->dropIndex('tickets_assigned_to_idx');
            $table->dropIndex('tickets_category_idx');
            $table->dropIndex('tickets_created_at_idx');
            $table->dropIndex('tickets_sla_due_at_idx');
            $table->dropIndex('tickets_is_escalated_status_idx');
            $table->dropIndex('tickets_user_id_created_at_idx');
        });
    }
};
