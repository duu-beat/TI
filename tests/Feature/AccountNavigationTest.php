<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_accesses_account_options_from_avatar_menu(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

        $response = $this->actingAs($client)->get(route('client.dashboard'));

        $response->assertOk();
        $response->assertSee('account-menu', false);
        $response->assertSee(route('client.profile'), false);
        $response->assertSee('Segurança da Conta');
        $response->assertDontSee('👤 Meu Perfil', false);
    }

    public function test_admin_accesses_account_options_from_avatar_menu(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('account-menu', false);
        $response->assertSee(route('admin.profile'), false);
        $response->assertDontSee('👤 Meu Perfil', false);
    }

    public function test_profile_central_preserves_identity_and_security_sections(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

        $response = $this->actingAs($client)->get(route('client.profile'));

        $response->assertOk();
        $response->assertSee('Central da Conta');
        $response->assertSee('id="identidade-da-conta"', false);
        $response->assertSee('id="seguranca-da-conta"', false);
    }
}
