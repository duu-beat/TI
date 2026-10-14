<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Branch> */
class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company() . ' - Filial',
            'code' => $this->faker->unique()->bothify('FIL-###'),
            'address' => $this->faker->address(),
            'is_active' => true,
        ];
    }
}
