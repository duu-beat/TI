<?php

namespace Tests\Feature;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketChecklist;
use App\Models\User;
use App\Services\DashboardStatsService;
use App\Support\DashboardCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class InternalSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_cannot_reply_to_another_users_ticket(): void
    {
        $owner = User::factory()->create(['role' => 'client']);
        $otherClient = User::factory()->create(['role' => 'client']);
        $ticket = $this->makeTicket($owner);

        $this->actingAs($otherClient)
            ->post(route('client.tickets.reply', $ticket), ['message' => 'Tentativa de acesso indevido'])
            ->assertForbidden();

        $this->assertDatabaseMissing('ticket_messages', [
            'ticket_id' => $ticket->id,
            'message' => 'Tentativa de acesso indevido',
        ]);
    }

    public function test_admin_cannot_toggle_checklist_item_from_a_different_ticket(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $owner = User::factory()->create(['role' => 'client']);
        $ticket = $this->makeTicket($owner);
        $otherTicket = $this->makeTicket($owner, ['subject' => 'Outro chamado']);
        $item = TicketChecklist::create([
            'ticket_id' => $otherTicket->id,
            'task' => 'Checklist de outro chamado',
            'order' => 1,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.tickets.checklist.toggle', ['ticket' => $ticket, 'item' => $item]))
            ->assertNotFound();
    }

    public function test_admin_dashboard_keeps_operational_indicators_in_the_loaded_content(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);
        $agent = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

        $this->makeTicket($client, [
            'priority' => TicketPriority::HIGH,
            'assigned_to' => null,
        ]);
        $this->makeTicket($client, [
            'assigned_to' => $agent->id,
            'status' => TicketStatus::RESOLVED,
            'resolved_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk()
            ->assertSee('sem responsável')
            ->assertSee('Top agentes')
            ->assertSee('priority=high&amp;open_only=1', false);

        $content = $response->getContent();
        $this->assertGreaterThan(
            strpos($content, 'x-show="loaded"'),
            strpos($content, 'Top agentes')
        );
    }

    public function test_admin_open_high_priority_filter_excludes_resolved_tickets(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $openTicket = $this->makeTicket($client, [
            'subject' => 'Incidente crítico aberto',
            'priority' => TicketPriority::HIGH,
            'status' => TicketStatus::IN_PROGRESS,
        ]);
        $resolvedTicket = $this->makeTicket($client, [
            'subject' => 'Incidente crítico resolvido',
            'priority' => TicketPriority::HIGH,
            'status' => TicketStatus::RESOLVED,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.tickets.index', ['priority' => 'high', 'open_only' => 1]))
            ->assertOk()
            ->assertSee($openTicket->subject)
            ->assertDontSee($resolvedTicket->subject);
    }

    public function test_internal_dashboard_stats_are_portable_and_cache_invalidates(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = $this->makeTicket($client, [
            'assigned_to' => $admin->id,
            'status' => TicketStatus::RESOLVED,
            'resolved_at' => now(),
            'resolution_time_minutes' => 180,
        ]);

        $data = app(DashboardStatsService::class)->getAdminDashboardData();

        // 180 min / 60 = 3.0 horas
        $this->assertSame(3.0, (float) $data['avgResolution']);

        Cache::put(DashboardCache::ADMIN_STATS, ['cached' => true], 600);
        $ticket->update(['subject' => 'Atualizado']);

        $this->assertFalse(Cache::has(DashboardCache::ADMIN_STATS));
    }

    private function makeTicket(User $owner, array $attributes = []): Ticket
    {
        return Ticket::create(array_merge([
            'user_id' => $owner->id,
            'subject' => 'Chamado de Teste',
            'description' => 'Descrição do chamado.',
            'status' => TicketStatus::NEW,
            'priority' => TicketPriority::MEDIUM,
        ], $attributes));
    }
}
