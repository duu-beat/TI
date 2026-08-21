<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_render_complete_public_landing_page(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertViewIs('public.home')
            ->assertSee('TI que funciona.')
            ->assertSee('Tudo o que sua equipe precisa para manter a TI em movimento.')
            ->assertSee('Um fluxo simples para quem solicita, atende e supervisiona.')
            ->assertSee('Segurança não precisa ficar separada da operação.')
            ->assertSee('Mais clareza para sua TI começa com uma boa operação.')
            ->assertSee('Pular para o conteúdo principal')
            ->assertSee('rel="canonical"', false)
            ->assertSee('og:locale', false)
            ->assertDontSee('Business Standard')
            ->assertDontSee('livewire/livewire.js', false);
    }

    public function test_client_is_redirected_from_home_to_client_dashboard(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

        $this->actingAs($client)
            ->get(route('home'))
            ->assertRedirect(route('client.dashboard'));
    }

    public function test_admin_is_redirected_from_home_to_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->get(route('home'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_master_is_redirected_from_home_to_security_dashboard(): void
    {
        $master = User::factory()->create(['role' => User::ROLE_MASTER]);

        $this->actingAs($master)
            ->get(route('home'))
            ->assertRedirect(route('master.dashboard'));
    }

    public function test_public_sitemap_lists_institutional_pages(): void
    {
        $response = $this->get(route('sitemap'));

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('home'), false)
            ->assertSee(route('services'), false)
            ->assertSee(route('contact'), false);
    }
}
