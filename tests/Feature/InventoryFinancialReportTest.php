<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\AssetContract;
use App\Models\AssetWarranty;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryFinancialReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_consolidated_inventory_report(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $supplier = Supplier::factory()->create(['name' => 'Fornecedor Analítico']);
        $asset = Asset::factory()->create(['supplier_id' => $supplier->id]);
        AssetWarranty::factory()->create(['asset_id' => $asset->id, 'supplier_id' => $supplier->id]);
        AssetContract::factory()->create(['asset_id' => $asset->id, 'supplier_id' => $supplier->id, 'amount' => 1250.50]);

        $this->actingAs($admin)
            ->get(route('admin.reports.inventory-financial'))
            ->assertOk()
            ->assertSee('Fornecedor Analítico')
            ->assertSee('R$ 1.250,50', false)
            ->assertSee(__('assets.financial_report.title'));
    }

    public function test_report_filter_limits_supplier_rows_and_totals(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $selected = Supplier::factory()->create(['name' => 'Fornecedor Selecionado']);
        $other = Supplier::factory()->create(['name' => 'Fornecedor Oculto']);
        $selectedAsset = Asset::factory()->create(['supplier_id' => $selected->id]);
        $otherAsset = Asset::factory()->create(['supplier_id' => $other->id]);
        AssetContract::factory()->create(['asset_id' => $selectedAsset->id, 'supplier_id' => $selected->id, 'amount' => 100]);
        AssetContract::factory()->create(['asset_id' => $otherAsset->id, 'supplier_id' => $other->id, 'amount' => 900]);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.inventory-financial', ['supplier_id' => $selected->id]))
            ->assertOk();
        $table = str($response->getContent())->before('</tbody>')->after('<tbody');
        $this->assertStringContainsString('Fornecedor Selecionado', $table);
        $this->assertStringNotContainsString('Fornecedor Oculto', $table);
        $this->assertStringContainsString('R$ 100,00', $table);
        $this->assertStringNotContainsString('R$ 900,00', $table);
    }

    public function test_client_cannot_view_inventory_financial_report(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $this->actingAs($client)->get(route('admin.reports.inventory-financial'))->assertForbidden();
    }
}
