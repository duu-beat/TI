<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_without_two_factor_is_redirected_to_configure_security_before_accessing_dashboard(): void
    {
        $master = User::factory()->create([
            'role' => User::ROLE_MASTER,
            'email_verified_at' => now(),
        ]);

        $response = $this->post(route('master.login.store'), [
            'email' => $master->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($master);
        $response->assertRedirect(route('master.dashboard'));

        $this->actingAs($master)
            ->get(route('master.dashboard'))
            ->assertRedirect(route('master.profile') . '#seguranca-da-conta')
            ->assertSessionHas('warning');

        $this->actingAs($master)
            ->get(route('master.profile'))
            ->assertOk();
    }

    public function test_master_with_confirmed_two_factor_is_challenged_before_accessing_security_dashboard(): void
    {
        $master = User::factory()->create([
            'role' => User::ROLE_MASTER,
            'email_verified_at' => now(),
            'two_factor_secret' => encrypt('TEST-2FA-SECRET'),
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->post(route('master.login.store'), [
            'email' => $master->email,
            'password' => 'password',
            'remember' => true,
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('two-factor.login'));
        $response->assertSessionHas('login.id', $master->id);
        $response->assertSessionHas('login.remember', true);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $master->id,
            'action' => 'Desafio 2FA Master',
        ]);
    }
}
