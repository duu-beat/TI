<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TicketAttachmentExperienceTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_creation_persists_attachment_metadata_for_previews(): void
    {
        Storage::fake('public');
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $image = UploadedFile::fake()->image('erro-tela.png', 800, 600);
        $pdf = UploadedFile::fake()->create('diagnostico.pdf', 120, 'application/pdf');

        $response = $this->actingAs($client)->post(route('client.tickets.store'), [
            'category' => 'hardware',
            'subject' => 'Notebook não inicia',
            'description' => 'O notebook apresenta uma tela de erro ao iniciar.',
            'priority' => 'medium',
            'attachments' => [$image, $pdf],
        ]);

        $response->assertRedirect();

        $ticket = Ticket::firstOrFail();
        $attachments = $ticket->messages()->firstOrFail()->attachments()->orderBy('id')->get();

        $response->assertRedirect(route('client.tickets.show', $ticket));
        $this->assertCount(2, $attachments);
        $this->assertTrue($attachments->first()->isImage());
        $this->assertTrue($attachments->last()->isPdf());
        $this->assertNotNull($attachments->first()->mime_type);
        $this->assertGreaterThan(0, $attachments->first()->size);
        Storage::disk('public')->assertExists($attachments->first()->file_path);
        Storage::disk('public')->assertExists($attachments->last()->file_path);
    }

    public function test_internal_note_persists_attachment_metadata_for_administrator(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $ticket = Ticket::factory()->create();
        $attachment = UploadedFile::fake()->create('analise.txt', 8, 'text/plain');

        $response = $this->actingAs($admin)->post(route('admin.tickets.reply', $ticket), [
            'message' => 'Nota interna com diagnóstico.',
            'is_internal' => true,
            'time_spent' => 10,
            'attachments' => [$attachment],
        ]);

        $response->assertSessionHas('success', 'Nota interna adicionada.');

        $message = $ticket->fresh()->messages()->latest()->firstOrFail();
        $storedAttachment = $message->attachments()->firstOrFail();
        $this->assertTrue($message->is_internal);
        $this->assertSame('analise.txt', $storedAttachment->name);
        $this->assertSame('text/plain', $storedAttachment->mime_type);
        Storage::disk('public')->assertExists($storedAttachment->file_path);
    }

    public function test_new_error_pages_render_with_safe_recovery_actions(): void
    {
        $this->view('errors.401')->assertSee('Entre na sua conta para continuar.');
        $this->view('errors.419')->assertSee('esta ação expirou');
        $this->view('errors.429')->assertSee('Muitas solicitações em pouco tempo.');
        $this->view('errors.503')->assertSee('Estamos preparando uma melhoria.');
    }
}
