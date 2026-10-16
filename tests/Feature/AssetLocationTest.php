<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\Branch;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetLocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_asset_with_branch_and_sector(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $branch = Branch::factory()->create(['name' => 'Filial Centro']);
        $sector = Sector::factory()->create([
            'branch_id' => $branch->id,
            'name' => 'Tecnologia',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.assets.store'), [
                'name' => 'Notebook de inventário',
                'tag' => 'INV-LOC-001',
                'type' => 'Laptop',
                'status' => 'active',
                'branch_id' => $branch->id,
                'sector_id' => $sector->id,
            ])
            ->assertRedirect(route('admin.assets.index'));

        $this->assertDatabaseHas('assets', [
            'tag' => 'INV-LOC-001',
            'branch_id' => $branch->id,
            'sector_id' => $sector->id,
        ]);
    }

    public function test_sector_must_belong_to_the_selected_branch(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $branch = Branch::factory()->create();
        $otherBranch = Branch::factory()->create();
        $sector = Sector::factory()->create(['branch_id' => $otherBranch->id]);

        $this->actingAs($admin)
            ->from(route('admin.assets.create'))
            ->post(route('admin.assets.store'), [
                'name' => 'Notebook inválido',
                'tag' => 'INV-LOC-002',
                'type' => 'Laptop',
                'status' => 'active',
                'branch_id' => $branch->id,
                'sector_id' => $sector->id,
            ])
            ->assertSessionHasErrors('sector_id');

        $this->assertDatabaseMissing('assets', ['tag' => 'INV-LOC-002']);
    }

    public function test_existing_assets_can_remain_without_location(): void
    {
        $asset = Asset::factory()->create([
            'branch_id' => null,
            'sector_id' => null,
        ]);

        $this->assertNull($asset->branch_id);
        $this->assertNull($asset->sector_id);
    }

    public function test_admin_asset_forms_expose_active_branches_and_sectors(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $branch = Branch::factory()->create(['name' => 'Filial Norte']);
        $sector = Sector::factory()->create([
            'branch_id' => $branch->id,
            'name' => 'Operações',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.assets.create'))
            ->assertOk()
            ->assertSee('Filial Norte')
            ->assertSee('Operações');

        $asset = Asset::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.assets.edit', $asset))
            ->assertOk()
            ->assertSee('Filial Norte')
            ->assertSee('Operações');
    }

    public function test_client_cannot_access_or_mutate_inventory_locations(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

        $this->actingAs($client)
            ->get(route('admin.inventory.locations.index'))
            ->assertForbidden();

        $this->actingAs($client)
            ->post(route('admin.inventory.locations.branches.store'), ['name' => 'Filial indevida'])
            ->assertForbidden();
    }

    public function test_admin_can_manage_branches_and_sectors(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->get(route('admin.inventory.locations.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->post(route('admin.inventory.locations.branches.store'), [
                'name' => 'Filial Sul',
                'code' => 'SUL',
                'address' => 'Rua das Redes, 10',
            ])
            ->assertRedirect();

        $branch = Branch::where('code', 'SUL')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.inventory.locations.sectors.store'), [
                'branch_id' => $branch->id,
                'name' => 'Infraestrutura',
            ])
            ->assertRedirect();

        $sector = Sector::where('branch_id', $branch->id)->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.inventory.locations.sectors.update', $sector), [
                'branch_id' => $branch->id,
                'name' => 'Infraestrutura e Redes',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('sectors', [
            'id' => $sector->id,
            'name' => 'Infraestrutura e Redes',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.inventory.locations.branches.destroy', $branch))
            ->assertRedirect();

        $this->assertSoftDeleted('branches', ['id' => $branch->id]);
    }

    public function test_sector_name_must_be_unique_inside_each_branch(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $branch = Branch::factory()->create();

        Sector::factory()->create([
            'branch_id' => $branch->id,
            'name' => 'Suporte',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.inventory.locations.sectors.store'), [
                'branch_id' => $branch->id,
                'name' => 'Suporte',
            ])
            ->assertSessionHasErrors('name');
    }

    public function test_admin_can_transfer_asset_and_records_complete_location_history(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $recipient = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $oldBranch = Branch::factory()->create(['name' => 'Filial Antiga']);
        $newBranch = Branch::factory()->create(['name' => 'Filial Nova']);
        $oldSector = Sector::factory()->create(['branch_id' => $oldBranch->id, 'name' => 'Antigo']);
        $newSector = Sector::factory()->create(['branch_id' => $newBranch->id, 'name' => 'Novo']);
        $asset = Asset::factory()->create([
            'user_id' => null,
            'branch_id' => $oldBranch->id,
            'sector_id' => $oldSector->id,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.assets.transfer.store', $asset), [
                'user_id' => $recipient->id,
                'branch_id' => $newBranch->id,
                'sector_id' => $newSector->id,
                'reason' => 'Entrega para novo projeto',
            ])
            ->assertRedirect(route('admin.assets.show', $asset));

        $asset->refresh();
        $this->assertSame($recipient->id, $asset->user_id);
        $this->assertSame($newBranch->id, $asset->branch_id);
        $this->assertSame($newSector->id, $asset->sector_id);
        $this->assertDatabaseHas('asset_histories', [
            'asset_id' => $asset->id,
            'action' => 'transfer',
            'old_branch_id' => $oldBranch->id,
            'new_branch_id' => $newBranch->id,
            'old_sector_id' => $oldSector->id,
            'new_sector_id' => $newSector->id,
            'new_user_id' => $recipient->id,
        ]);
    }

    public function test_transfer_rejects_sector_from_another_branch_without_changing_asset(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $branch = Branch::factory()->create();
        $otherBranch = Branch::factory()->create();
        $sector = Sector::factory()->create(['branch_id' => $otherBranch->id]);
        $asset = Asset::factory()->create(['branch_id' => $branch->id, 'sector_id' => null]);

        $this->actingAs($admin)
            ->post(route('admin.assets.transfer.store', $asset), [
                'branch_id' => $branch->id,
                'sector_id' => $sector->id,
                'reason' => 'Tentativa inválida',
            ])
            ->assertSessionHasErrors('sector_id');

        $asset->refresh();
        $this->assertNull($asset->sector_id);
        $this->assertDatabaseMissing('asset_histories', ['asset_id' => $asset->id, 'action' => 'transfer']);
    }

    public function test_client_cannot_access_asset_transfer(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
        $asset = Asset::factory()->create();

        $this->actingAs($client)
            ->get(route('admin.assets.transfer.create', $asset))
            ->assertForbidden();
    }
}
