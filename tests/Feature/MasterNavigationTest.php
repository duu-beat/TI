<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_menu_displays_supervision_items_and_hides_admin_operational_items(): void
    {
        $master = User::factory()->create([
            'role' => User::ROLE_MASTER,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($master)->get(route('master.dashboard'));

        $response->assertOk();
        $response->assertSee('Centro de Segurança e Governança');
        $response->assertSee('Saúde do sistema');
        $response->assertSee('Identidades e acessos');
        $response->assertDontSee('Painel Admin');
        $response->assertDontSee('Agenda de Visitas');
        $response->assertDontSee('Relatórios');
    }

    public function test_master_cannot_access_the_admin_operational_dashboard(): void
    {
        $master = User::factory()->create([
            'role' => User::ROLE_MASTER,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($master)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_master_can_access_the_system_health_panel(): void
    {
        $master = User::factory()->create([
            'role' => User::ROLE_MASTER,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($master)->get(route('master.health'));

        $response->assertOk();
        $response->assertSee('Saúde e Diagnóstico da Infraestrutura');
        $response->assertSee('Status dos Componentes do Sistema');
    }

    public function test_master_resolution_of_escalated_incident_records_metrics_and_audit(): void
    {
        $master = User::factory()->create([
            'role' => User::ROLE_MASTER,
            'email_verified_at' => now(),
        ]);
        $ticket = \App\Models\Ticket::factory()->create([
            'is_escalated' => true,
            'status' => \App\Enums\TicketStatus::IN_PROGRESS,
            'sla_due_at' => now()->subHour(),
        ]);

        $this->actingAs($master)
            ->post(route('master.tickets.resolve', $ticket), [
                'solution' => 'Intervenção de segurança concluída.',
            ])
            ->assertSessionHas('success');

        $ticket->refresh();
        $this->assertSame(\App\Enums\TicketStatus::RESOLVED, $ticket->status);
        $this->assertFalse($ticket->is_escalated);
        $this->assertNotNull($ticket->resolved_at);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'Ticket Resolved',
            'level' => 'SUCCESS',
        ]);
        $this->assertDatabaseHas('ticket_messages', [
            'ticket_id' => $ticket->id,
            'message' => "Chamado resolvido pela equipe de Segurança/Infraestrutura.\n\nSolução técnica: Intervenção de segurança concluída.",
        ]);
    }
}
