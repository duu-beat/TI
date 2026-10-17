<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\AssetWarranty;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AssetWarrantyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_remove_asset_warranty(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $asset = Asset::factory()->create();
        $supplier = Supplier::factory()->create(['name' => 'Garantia Tech']);

        $this->actingAs($admin)
            ->get(route('admin.assets.warranties.create', $asset))
            ->assertOk()
            ->assertSee(__('assets.warranties.new'));

        $this->actingAs($admin)
            ->post(route('admin.assets.warranties.store', $asset), [
                'supplier_id' => $supplier->id,
                'type' => AssetWarranty::TYPE_SUPPLIER,
                'contract_number' => 'CTR-001',
                'invoice_number' => 'NF-001',
                'starts_at' => '2026-01-01',
                'expires_at' => '2027-01-01',
            ])
            ->assertRedirect(route('admin.assets.show', $asset));

        $warranty = $asset->warranties()->firstOrFail();
        $this->assertDatabaseHas('asset_warranties', ['id' => $warranty->id, 'supplier_id' => $supplier->id]);

        $this->actingAs($admin)
            ->put(route('admin.assets.warranties.update', [$asset, $warranty]), [
                'type' => AssetWarranty::TYPE_EXTENDED,
                'starts_at' => '2026-01-01',
                'expires_at' => '2028-01-01',
            ])
            ->assertRedirect(route('admin.assets.show', $asset));

        $this->actingAs($admin)
            ->delete(route('admin.assets.warranties.destroy', [$asset, $warranty]))
            ->assertRedirect(route('admin.assets.show', $asset));

        $this->assertSoftDeleted('asset_warranties', ['id' => $warranty->id]);
    }

    public function test_warranty_status_is_calculated_from_expiration_date(): void
    {
        Carbon::setTestNow('2026-10-17 12:00:00');
        $active = AssetWarranty::factory()->create(['expires_at' => '2027-10-17']);
        $expiring = AssetWarranty::factory()->create(['expires_at' => '2026-12-01']);
        $expired = AssetWarranty::factory()->create(['expires_at' => '2026-10-16']);

        $this->assertSame('active', $active->status());
        $this->assertSame('expiring', $expiring->status());
        $this->assertSame('expired', $expired->status());
        Carbon::setTestNow();
    }

    public function test_client_cannot_manage_asset_warranties(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $asset = Asset::factory()->create();
        $warranty = AssetWarranty::factory()->create(['asset_id' => $asset->id]);

        $this->actingAs($client)->get(route('admin.assets.warranties.create', $asset))->assertForbidden();
        $this->actingAs($client)->post(route('admin.assets.warranties.store', $asset), [
            'type' => AssetWarranty::TYPE_MANUFACTURER,
            'starts_at' => '2026-01-01',
            'expires_at' => '2027-01-01',
        ])->assertForbidden();
        $this->actingAs($client)->delete(route('admin.assets.warranties.destroy', [$asset, $warranty]))->assertForbidden();
    }

    public function test_warranty_from_another_asset_returns_not_found(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $asset = Asset::factory()->create();
        $otherAsset = Asset::factory()->create();
        $warranty = AssetWarranty::factory()->create(['asset_id' => $otherAsset->id]);

        $this->actingAs($admin)
            ->get(route('admin.assets.warranties.edit', [$asset, $warranty]))
            ->assertNotFound();
    }

    public function test_invalid_warranty_dates_are_rejected(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $asset = Asset::factory()->create();

        $this->actingAs($admin)
            ->from(route('admin.assets.warranties.create', $asset))
            ->post(route('admin.assets.warranties.store', $asset), [
                'type' => AssetWarranty::TYPE_MANUFACTURER,
                'starts_at' => '2027-01-02',
                'expires_at' => '2027-01-01',
            ])
            ->assertSessionHasErrors('expires_at');

        $this->assertDatabaseCount('asset_warranties', 0);
    }
}
