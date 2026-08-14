<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetQrCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_assets_receive_a_unique_qr_token_when_created(): void
    {
        $firstAsset = Asset::factory()->create();
        $secondAsset = Asset::factory()->create();

        $this->assertNotNull($firstAsset->qr_token);
        $this->assertNotNull($secondAsset->qr_token);
        $this->assertNotSame($firstAsset->qr_token, $secondAsset->qr_token);
        $this->assertTrue((bool) preg_match('/^[0-9a-f-]{36}$/i', $firstAsset->qr_token));
    }

    public function test_admin_can_open_asset_profile_from_qr_token(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $asset = Asset::factory()->create(['name' => 'Notebook QR de Teste']);

        $response = $this->actingAs($admin)->get(route('admin.assets.scan', $asset->qr_token));

        $response->assertOk();
        $response->assertSee('Ficha do Ativo');
        $response->assertSee('Notebook QR de Teste');
        $response->assertSee(route('admin.assets.qr-code', $asset), false);
        $response->assertSee(route('admin.assets.qr-label', $asset), false);
    }

    public function test_qr_svg_is_available_only_to_inventory_administrators(): void
    {
        $asset = Asset::factory()->create();
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

        $this->actingAs($client)
            ->get(route('admin.assets.qr-code', $asset))
            ->assertForbidden();

        $this->actingAs($client)
            ->get(route('admin.assets.qr-label', $asset))
            ->assertForbidden();

        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($admin)
            ->get(route('admin.assets.qr-code', $asset));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/svg+xml; charset=UTF-8');
        $response->assertSee('<svg', false);

        $this->actingAs($admin)
            ->get(route('admin.assets.qr-label', $asset))
            ->assertOk()
            ->assertSee('Imprimir etiqueta');
    }

    public function test_invalid_qr_token_returns_not_found_for_administrator(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->get(route('admin.assets.scan', 'b90731d4-e3c8-4cc5-bfaa-e0d381b2d66d'))
            ->assertNotFound();
    }
}
