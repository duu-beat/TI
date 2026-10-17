<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\AssetContract;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AssetContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_remove_contract(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $asset = Asset::factory()->create();
        $supplier = Supplier::factory()->create();

        $this->actingAs($admin)->post(route('admin.assets.contracts.store', $asset), [
            'supplier_id' => $supplier->id, 'number' => 'CTR-100', 'type' => 'maintenance',
            'starts_at' => '2026-01-01', 'expires_at' => '2027-01-01', 'amount' => '2500.50',
            'auto_renew' => '1', 'invoice_number' => 'NF-100',
        ])->assertRedirect(route('admin.assets.show', $asset));

        $contract = $asset->contracts()->firstOrFail();
        $this->assertDatabaseHas('asset_contracts', ['id' => $contract->id, 'number' => 'CTR-100', 'auto_renew' => 1]);

        $this->actingAs($admin)->put(route('admin.assets.contracts.update', [$asset, $contract]), [
            'number' => 'CTR-101', 'type' => 'service', 'starts_at' => '2026-01-01', 'expires_at' => '2028-01-01',
        ])->assertRedirect(route('admin.assets.show', $asset));

        $this->actingAs($admin)->delete(route('admin.assets.contracts.destroy', [$asset, $contract]))->assertRedirect(route('admin.assets.show', $asset));
        $this->assertSoftDeleted('asset_contracts', ['id' => $contract->id]);
    }

    public function test_contract_status_is_calculated_from_expiration(): void
    {
        Carbon::setTestNow('2026-10-17 12:00:00');
        $active = AssetContract::factory()->create(['expires_at' => '2027-10-17']);
        $expiring = AssetContract::factory()->create(['expires_at' => '2026-12-01']);
        $expired = AssetContract::factory()->create(['expires_at' => '2026-10-16']);
        $this->assertSame('active', $active->status());
        $this->assertSame('expiring', $expiring->status());
        $this->assertSame('expired', $expired->status());
        Carbon::setTestNow();
    }

    public function test_client_cannot_manage_contracts(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $asset = Asset::factory()->create();
        $contract = AssetContract::factory()->create(['asset_id' => $asset->id]);
        $this->actingAs($client)->get(route('admin.assets.contracts.create', $asset))->assertForbidden();
        $this->actingAs($client)->delete(route('admin.assets.contracts.destroy', [$asset, $contract]))->assertForbidden();
    }

    public function test_contract_from_another_asset_returns_not_found(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $asset = Asset::factory()->create();
        $other = Asset::factory()->create();
        $contract = AssetContract::factory()->create(['asset_id' => $other->id]);
        $this->actingAs($admin)->get(route('admin.assets.contracts.edit', [$asset, $contract]))->assertNotFound();
    }

    public function test_contract_dates_are_validated(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $asset = Asset::factory()->create();
        $this->actingAs($admin)->post(route('admin.assets.contracts.store', $asset), [
            'number' => 'CTR-INVALID', 'type' => 'maintenance', 'starts_at' => '2027-01-02', 'expires_at' => '2027-01-01',
        ])->assertSessionHasErrors('expires_at');
        $this->assertDatabaseCount('asset_contracts', 0);
    }
}
