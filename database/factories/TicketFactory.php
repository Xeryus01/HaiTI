<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        return [
            'code' => 'TEST-' . $this->faker->unique()->numberBetween(1000, 9999),
            'requester_id' => User::factory(),
            'assignee_id' => null,
            'asset_id' => null,
            'category' => 'IT_SUPPORT',
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph,
            'priority' => 'MEDIUM',
            'status' => Ticket::STATUS_OPEN,
        ];
    }
}
