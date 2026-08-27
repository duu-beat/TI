<?php

namespace Tests\Feature;

use App\Models\KnowledgeBase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HighPrioritySecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_cannot_access_administrative_inventory_wiki_tags_or_visits(): void
    {
        $client = User::factory()->create([
            'role' => User::ROLE_CLIENT,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($client)->get(route('admin.assets.index'))->assertForbidden();
        $this->actingAs($client)->get(route('admin.wiki.index'))->assertForbidden();
        $this->actingAs($client)->get(route('admin.tags.index'))->assertForbidden();
        $this->actingAs($client)->get(route('admin.visits.index'))->assertForbidden();
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
}
