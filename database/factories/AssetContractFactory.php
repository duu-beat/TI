<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\AssetContract;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<AssetContract> */
class AssetContractFactory extends Factory
{
    protected $model = AssetContract::class;
    public function definition(): array
    {
        return ['asset_id' => Asset::factory(), 'number' => $this->faker->bothify('CTR-####'), 'type' => 'maintenance', 'starts_at' => now()->subMonth()->toDateString(), 'expires_at' => now()->addYear()->toDateString(), 'amount' => 1500, 'auto_renew' => false];
    }
}
