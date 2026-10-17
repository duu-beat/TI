<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\AssetWarranty;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<AssetWarranty> */
class AssetWarrantyFactory extends Factory
{
    protected $model = AssetWarranty::class;

    public function definition(): array
    {
        return [
            'asset_id' => Asset::factory(),
            'type' => AssetWarranty::TYPE_MANUFACTURER,
            'starts_at' => now()->subMonth()->toDateString(),
            'expires_at' => now()->addYear()->toDateString(),
            'contract_number' => $this->faker->bothify('GAR-####'),
        ];
    }
}
