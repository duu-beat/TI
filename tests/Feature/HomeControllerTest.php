<?php

namespace Tests\Feature;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_render_home_page(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertViewIs('public.home');
    }

    public function test_admin_sees_cached_dashboard_stats_in_home_view(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

        Ticket::query()->create([
            'user_id' => $client->id,
            'subject' => 'Ticket urgente',
            'description' => 'Falha crítica',
            'status' => TicketStatus::NEW->value,
            'priority' => TicketPriority::HIGH->value,
        ]);

        $response = $this->actingAs($admin)->get(route('home'));

        $response->assertOk();
        $response->assertViewHas('urgentCount', 1);
        $response->assertViewHas('openTickets', 1);
    }

    public function test_master_sees_master_stats_in_home_view(): void
    {
        $master = User::factory()->create(['role' => User::ROLE_MASTER]);
        User::factory()->create(['role' => User::ROLE_ADMIN]);
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

        Ticket::query()->create([
            'user_id' => $client->id,
            'subject' => 'Ticket escalado',
            'description' => 'Incidente de segurança',
            'status' => TicketStatus::IN_PROGRESS->value,
            'priority' => TicketPriority::HIGH->value,
            'is_escalated' => true,
        ]);

        $response = $this->actingAs($master)->get(route('home'));

        $response->assertOk();
        $response->assertViewHas('escalatedCount', 1);
        $response->assertViewHas('adminsCount', 1);
    }
}
