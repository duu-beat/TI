<?php

namespace Tests\Feature;

use App\Models\Asset;
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
}
