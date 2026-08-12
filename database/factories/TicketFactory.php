<?php

namespace Database\Factories;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category' => 'Hardware',
            'subject' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => TicketStatus::NEW,
            'priority' => TicketPriority::MEDIUM,
            'sla_due_at' => now()->addHours(24),
        ];
    }
}
