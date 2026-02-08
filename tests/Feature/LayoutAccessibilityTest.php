<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LayoutAccessibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_layout_contains_accessibility_attributes_for_mobile_menu(): void
    {
        $response = $this->get(route('faq'));

        $response->assertOk();
        $response->assertSee('aria-controls="mobile-main-menu"', false);
        $response->assertSee('aria-label="Alternar menu principal"', false);
        $response->assertSee('x-ref="mobileFirstLink"', false);
        $response->assertSee('@click="toggleMobileMenu($event.currentTarget)"', false);
        $response->assertSee('rel="noopener noreferrer"', false);
    }

    public function test_authenticated_layout_contains_accessible_logout_modal_markup(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_CLIENT]);

        $response = $this->actingAs($user)->get(route('client.dashboard'));

        $response->assertOk();
        $response->assertSee('id="logout-modal"', false);
        $response->assertSee('role="dialog"', false);
        $response->assertSee('aria-modal="true"', false);
        $response->assertSee('aria-controls="logout-modal"', false);
    }

    public function test_faq_contains_keyboard_navigation_attributes(): void
    {
        DB::table('faqs')->insert([
            'question' => 'Como abrir um chamado?',
            'answer' => 'Use o portal para criar um novo chamado.',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(route('faq'));

        $response->assertOk();
        $response->assertSee('data-faq-trigger', false);
        $response->assertSee('@keydown.down.prevent="focusNext(', false);
        $response->assertSee('@keydown.up.prevent="focusPrevious(', false);
        $response->assertSee('@keydown.home.prevent="focusFirst()"', false);
        $response->assertSee('@keydown.end.prevent="focusLast()"', false);
    }
}
