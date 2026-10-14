<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Sector;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Sector> */
class SectorFactory extends Factory
{
    protected $model = Sector::class;

    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'name' => $this->faker->unique()->randomElement(['TI', 'Financeiro', 'Comercial', 'RH', 'Operações']),
            'is_active' => true,
        ];
    }
}
