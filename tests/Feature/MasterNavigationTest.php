<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_menu_displays_security_items_and_hides_admin_operational_items(): void
    {
        $master = User::factory()->create([
            'role' => User::ROLE_MASTER,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($master)->get(route('master.dashboard'));

        $response->assertOk();
        $response->assertSee('Saúde do Sistema');
        $response->assertDontSee('📅 Agenda de Visitas', false);
        $response->assertDontSee('🏷️ Tags', false);
        $response->assertDontSee('📊 Relatórios', false);
        $response->assertDontSee('👤 Identidade', false);
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
}
