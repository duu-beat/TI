<?php

namespace Tests\Feature;

use App\Models\KnowledgeBase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientKnowledgeBaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_sees_only_published_knowledge_base_articles(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $author = User::factory()->create(['role' => User::ROLE_ADMIN]);

        KnowledgeBase::create([
            'title' => 'Configurar acesso à VPN',
            'content' => 'Procedimento para configurar a VPN corporativa.',
            'category' => 'Redes',
            'author_id' => $author->id,
            'is_published' => true,
        ]);
        KnowledgeBase::create([
            'title' => 'Rascunho confidencial',
            'content' => 'Este conteúdo não deve ser exibido ao cliente.',
            'category' => 'Segurança',
            'author_id' => $author->id,
            'is_published' => false,
        ]);

        $this->actingAs($client)
            ->get(route('client.knowledge.index'))
            ->assertOk()
            ->assertSee('Base de Conhecimento')
            ->assertSee('Configurar acesso à VPN')
            ->assertDontSee('Rascunho confidencial');
    }

    public function test_client_can_open_a_published_article_and_view_is_counted(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $author = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $article = KnowledgeBase::create([
            'title' => 'Trocar senha corporativa',
            'content' => 'Siga o procedimento de troca de senha.',
            'category' => 'Acessos',
            'author_id' => $author->id,
            'is_published' => true,
            'views_count' => 0,
        ]);

        $this->actingAs($client)
            ->get(route('client.knowledge.show', $article))
            ->assertOk()
            ->assertSee('Trocar senha corporativa')
            ->assertSee('Siga o procedimento de troca de senha.');

        $this->assertSame(1, $article->fresh()->views_count);
    }

    public function test_suggestions_endpoint_returns_only_relevant_published_articles(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $author = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $published = KnowledgeBase::create([
            'title' => 'Conectar na VPN',
            'content' => 'Use o cliente VPN para acesso remoto seguro.',
            'category' => 'Redes',
            'author_id' => $author->id,
            'is_published' => true,
        ]);
        KnowledgeBase::create([
            'title' => 'Senha VPN de teste',
            'content' => 'Rascunho não publicado.',
            'category' => 'Redes',
            'author_id' => $author->id,
            'is_published' => false,
        ]);

        $this->actingAs($client)
            ->getJson(route('client.knowledge.suggestions', ['q' => 'VPN']))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $published->id)
            ->assertJsonPath('0.url', route('client.knowledge.show', $published));
    }

    public function test_ticket_form_exposes_knowledge_suggestions_without_removing_attachment_preview(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

        $this->actingAs($client)
            ->get(route('client.tickets.create'))
            ->assertOk()
            ->assertSee('Talvez este procedimento ajude')
            ->assertSee('knowledgeSuggestionsUrl', false)
            ->assertSee('attachmentUploader()', false);
    }
}
