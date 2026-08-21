<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\AssetResponsibilityTerm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AssetResponsibilityTermTest extends TestCase
{
    use RefreshDatabase;

    private const SIGNATURE_DATA_URI = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

    public function test_admin_can_issue_a_delivery_term_for_an_asset(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $recipient = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $asset = Asset::factory()->create(['user_id' => null, 'tag' => 'NB-TERM-001']);

        $this->actingAs($admin)
            ->post(route('admin.assets.terms.store', $asset), [
                'type' => AssetResponsibilityTerm::TYPE_DELIVERY,
                'recipient_id' => $recipient->id,
            ])
            ->assertRedirect();

        $term = AssetResponsibilityTerm::query()->sole();
        $this->assertSame(AssetResponsibilityTerm::STATUS_PENDING, $term->status);
        $this->assertSame($asset->id, $term->asset_id);
        $this->assertSame($recipient->id, $term->recipient_id);
        $this->assertStringContainsString('NB-TERM-001', $term->terms_text);
    }

    public function test_signature_page_prepares_canvas_for_responsive_pointer_input(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $recipient = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $asset = Asset::factory()->create();
        $term = AssetResponsibilityTerm::create([
            'asset_id' => $asset->id,
            'recipient_id' => $recipient->id,
            'issued_by' => $admin->id,
            'type' => AssetResponsibilityTerm::TYPE_DELIVERY,
            'status' => AssetResponsibilityTerm::STATUS_PENDING,
            'terms_text' => 'Termo pendente para validar o canvas.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.assets.terms.sign', [$asset, $term]))
            ->assertOk()
            ->assertSee('Assinatura do responsável')
            ->assertSee('ResizeObserver', false)
            ->assertSee('setTransform(ratio', false)
            ->assertSee("'pointerdown'", false)
            ->assertSee('canvas 1×1', false);
    }

    public function test_signing_delivery_term_updates_asset_records_audit_and_pdf(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $recipient = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $asset = Asset::factory()->create(['user_id' => null]);
        $term = AssetResponsibilityTerm::create([
            'asset_id' => $asset->id,
            'recipient_id' => $recipient->id,
            'issued_by' => $admin->id,
            'type' => AssetResponsibilityTerm::TYPE_DELIVERY,
            'status' => AssetResponsibilityTerm::STATUS_PENDING,
            'terms_text' => 'Termo de teste para assinatura.',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.assets.terms.store-signature', [$asset, $term]), [
                'signature' => self::SIGNATURE_DATA_URI,
            ])
            ->assertRedirect(route('admin.assets.show', $asset))
            ->assertSessionHas('success');

        $term->refresh();
        $asset->refresh();

        $this->assertTrue($term->isSigned());
        $this->assertNotNull($term->signed_at);
        $this->assertNotNull($term->signature_hash);
        $this->assertSame($recipient->id, $asset->user_id);
        Storage::disk('local')->assertExists($term->signature_path);
        Storage::disk('local')->assertExists($term->pdf_path);
        $this->assertDatabaseHas('asset_histories', [
            'asset_id' => $asset->id,
            'action' => 'responsibility_term_signed',
            'new_user_id' => $recipient->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.assets.terms.download', [$asset, $term]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->actingAs($admin)
            ->get(route('admin.assets.show', $asset))
            ->assertOk()
            ->assertSee('Termos de entrega e devolução')
            ->assertSee('Assinado')
            ->assertSee(route('admin.assets.terms.download', [$asset, $term]), false);
    }

    public function test_client_cannot_issue_or_sign_asset_terms(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $asset = Asset::factory()->create();

        $this->actingAs($client)
            ->get(route('admin.assets.terms.create', $asset))
            ->assertForbidden();
    }
}
