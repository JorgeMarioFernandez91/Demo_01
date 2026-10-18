<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Tenant is assigned from CurrentTenant when the ticket is created.
 *
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject' => fake()->sentence(6),
            'body' => fake()->paragraph(),
            'category' => fake()->randomElement(['billing', 'account', 'bug', 'feature-request', null]),
            'status' => fake()->randomElement(Ticket::STATUSES),
        ];
    }
}
