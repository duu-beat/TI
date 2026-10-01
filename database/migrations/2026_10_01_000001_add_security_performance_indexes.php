<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->index('role', 'users_role_idx');
        });

        Schema::table('notifications', function (Blueprint $table): void {
            $table->index(['user_id', 'read_at', 'created_at'], 'notifications_user_read_created_idx');
        });

        Schema::table('ticket_attachments', function (Blueprint $table): void {
            $table->index(['ticket_message_id', 'created_at'], 'ticket_attachments_message_created_idx');
        });

        Schema::table('technical_visits', function (Blueprint $table): void {
            $table->index(['status', 'scheduled_at'], 'technical_visits_status_scheduled_idx');
            $table->index(['ticket_id', 'scheduled_at'], 'technical_visits_ticket_scheduled_idx');
        });

        Schema::table('knowledge_bases', function (Blueprint $table): void {
            $table->index(['is_published', 'updated_at'], 'knowledge_bases_published_updated_idx');
            $table->index(['category', 'is_published'], 'knowledge_bases_category_published_idx');
        });

        Schema::table('canned_responses', function (Blueprint $table): void {
            $table->index(['category', 'title'], 'canned_responses_category_title_idx');
        });
    }

    public function down(): void
    {
        Schema::table('canned_responses', fn (Blueprint $table) => $table->dropIndex('canned_responses_category_title_idx'));
        Schema::table('knowledge_bases', function (Blueprint $table): void {
            $table->dropIndex('knowledge_bases_published_updated_idx');
            $table->dropIndex('knowledge_bases_category_published_idx');
        });
        Schema::table('technical_visits', function (Blueprint $table): void {
            $table->dropIndex('technical_visits_status_scheduled_idx');
            $table->dropIndex('technical_visits_ticket_scheduled_idx');
        });
        Schema::table('ticket_attachments', fn (Blueprint $table) => $table->dropIndex('ticket_attachments_message_created_idx'));
        Schema::table('notifications', fn (Blueprint $table) => $table->dropIndex('notifications_user_read_created_idx'));
        Schema::table('users', fn (Blueprint $table) => $table->dropIndex('users_role_idx'));
    }
};
