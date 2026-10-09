<?php

namespace Tests\Feature;

use App\Models\KnowledgeBase;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HighPrioritySecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_and_master_cannot_access_operational_admin_modules(): void
    {
        $client = User::factory()->create([
            'role' => User::ROLE_CLIENT,
            'email_verified_at' => now(),
        ]);

        $master = User::factory()->create([
            'role' => User::ROLE_MASTER,
            'email_verified_at' => now(),
        ]);

        $operationalRoutes = [
            'admin.dashboard',
            'admin.tickets.index',
            'admin.assets.index',
            'admin.wiki.index',
            'admin.tags.index',
            'admin.respostas-prontas.index',
            'admin.checklists.index',
            'admin.visits.index',
            'admin.reports.index',
        ];

        foreach ($operationalRoutes as $routeName) {
            $this->actingAs($client)->get(route($routeName))->assertForbidden();
            $this->actingAs($master)->get(route($routeName))->assertForbidden();
        }

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)->get(route('admin.respostas-prontas.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.checklists.index'))->assertOk();
    }

    public function test_wiki_search_and_category_filters_are_scoped_together(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        KnowledgeBase::create([
            'title' => 'VPN corporativa',
            'content' => 'Procedimento de acesso remoto.',
            'category' => 'Rede',
            'author_id' => $admin->id,
            'is_published' => true,
        ]);
        KnowledgeBase::create([
            'title' => 'Impressora',
            'content' => 'Configuração de impressora.',
            'category' => 'Hardware',
            'author_id' => $admin->id,
            'is_published' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.wiki.index', ['search' => 'Impressora', 'category' => 'Rede']))
            ->assertOk()
            ->assertSee('Nenhum artigo encontrado');
    }

    public function test_admin_can_access_and_schedule_technical_visits(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $ticket = Ticket::factory()->create(['user_id' => $client->id]);

        $this->actingAs($admin)
            ->get(route('admin.visits.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.visits.create', $ticket))
            ->assertOk();

        $this->actingAs($admin)
            ->post(route('admin.visits.store'), [
                'ticket_id' => $ticket->id,
                'scheduled_at' => now()->addDay()->format('Y-m-d H:i'),
                'address' => 'Rua da Tecnologia, 100',
                'notes' => 'Levar equipamento de diagnóstico.',
            ])
            ->assertRedirect(route('admin.tickets.show', $ticket));

        $this->assertDatabaseHas('technical_visits', [
            'ticket_id' => $ticket->id,
            'user_id' => $admin->id,
            'status' => 'scheduled',
        ]);
    }

    public function test_only_admin_can_view_and_export_reports(): void
    {
        $client = User::factory()->create([
            'role' => User::ROLE_CLIENT,
            'email_verified_at' => now(),
        ]);
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        foreach (['admin.reports.index', 'admin.reports.export-excel', 'admin.reports.export-pdf'] as $routeName) {
            $this->actingAs($client)->get(route($routeName))->assertForbidden();
        }

        $this->actingAs($admin)->get(route('admin.reports.index'))->assertOk();
        $this->actingAs($admin)
            ->get(route('admin.reports.export-excel'))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }
}
