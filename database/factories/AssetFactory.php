<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    protected $model = Asset::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(2, true),
            'tag' => 'TAG-' . $this->faker->unique()->numberBetween(1000, 9999),
            'serial_number' => $this->faker->unique()->bothify('SN-####-????'),
            'type' => $this->faker->randomElement(['Notebook', 'Monitor', 'Impressora', 'Teclado', 'Mouse']),
            'model' => $this->faker->word(),
            'brand' => $this->faker->randomElement(['Dell', 'HP', 'Lenovo', 'Apple', 'Logitech']),
            'status' => 'active',
        ];
    }
}
