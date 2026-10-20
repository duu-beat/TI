<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_suppliers_and_soft_delete_them(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->get(route('admin.inventory.suppliers.index'))
            ->assertOk()
            ->assertSee(__('assets.suppliers.title'))
            ->assertSee("\$dispatch('open-supplier-form')", false)
            ->assertSee('@open-supplier-form.window="showForm = true"', false);

        $this->actingAs($admin)
            ->post(route('admin.inventory.suppliers.store'), [
                'name' => 'Fornecedor Tech Brasil',
                'document' => '12.345.678/0001-90',
                'email' => 'contato@tech.test',
                'is_active' => '1',
            ])
            ->assertRedirect();

        $supplier = Supplier::query()->where('name', 'Fornecedor Tech Brasil')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.inventory.suppliers.update', $supplier), [
                'name' => 'Fornecedor Tech Brasil Atualizado',
                'document' => $supplier->document,
                'is_active' => '1',
            ])
            ->assertRedirect();

        $this->actingAs($admin)
            ->delete(route('admin.inventory.suppliers.destroy', $supplier))
            ->assertRedirect();

        $this->assertSoftDeleted('suppliers', ['id' => $supplier->id]);
    }

    public function test_client_cannot_manage_suppliers(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $supplier = Supplier::factory()->create();

        $this->actingAs($client)->get(route('admin.inventory.suppliers.index'))->assertForbidden();
        $this->actingAs($client)->post(route('admin.inventory.suppliers.store'), ['name' => 'Não permitido'])->assertForbidden();
        $this->actingAs($client)->delete(route('admin.inventory.suppliers.destroy', $supplier))->assertForbidden();
    }

    public function test_active_supplier_can_be_linked_to_asset(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $supplier = Supplier::factory()->create(['name' => 'Fornecedor do Ativo']);

        $this->actingAs($admin)
            ->post(route('admin.assets.store'), [
                'name' => 'Notebook com fornecedor',
                'tag' => 'SUP-001',
                'type' => 'Laptop',
                'status' => 'active',
                'supplier_id' => $supplier->id,
            ])
            ->assertRedirect(route('admin.assets.index'));

        $this->assertDatabaseHas('assets', ['tag' => 'SUP-001', 'supplier_id' => $supplier->id]);
    }

    public function test_inactive_or_deleted_supplier_cannot_be_linked_to_asset(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $inactive = Supplier::factory()->create(['is_active' => false]);
        $deleted = Supplier::factory()->create();
        $deleted->delete();

        foreach ([$inactive, $deleted] as $supplier) {
            $this->actingAs($admin)
                ->from(route('admin.assets.create'))
                ->post(route('admin.assets.store'), [
                    'name' => 'Ativo inválido',
                    'tag' => 'INVALID-' . $supplier->id,
                    'type' => 'Laptop',
                    'status' => 'active',
                    'supplier_id' => $supplier->id,
                ])
                ->assertSessionHasErrors('supplier_id');
        }

        $this->assertDatabaseCount('assets', 0);
    }

    public function test_asset_forms_expose_active_suppliers_only(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        Supplier::factory()->create(['name' => 'Fornecedor Ativo']);
        $inactive = Supplier::factory()->create(['name' => 'Fornecedor Inativo', 'is_active' => false]);
        $asset = Asset::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.assets.create'))
            ->assertOk()
            ->assertSee('Fornecedor Ativo')
            ->assertDontSee('Fornecedor Inativo');

        $this->actingAs($admin)
            ->get(route('admin.assets.edit', $asset))
            ->assertOk()
            ->assertSee('Fornecedor Ativo')
            ->assertDontSee('Fornecedor Inativo');
    }
}
