<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\InventoryRequest;
use App\Models\User;

class InventoryRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InventoryRequest::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'event_name' => fake()->words(3, true),
            'event_date_start' => fake()->dateTimeBetween('now', '+30 days'),
            'event_date_end' => fake()->dateTimeBetween('+31 days', '+60 days'),
            'status' => fake()->randomElement(['pending', 'approved', 'dispatched', 'completed', 'cancelled']),
            'notes_requester' => fake()->sentence(),
            'notes_approver' => fake()->optional()->sentence(),
            'approved_at' => fake()->optional()->dateTimeBetween('-30 days', 'now'),
            'dispatched_at' => fake()->optional()->dateTimeBetween('-20 days', 'now'),
            'completed_at' => fake()->optional()->dateTimeBetween('-10 days', 'now'),
            'user_id' => User::factory(),
            'approved_by_id' => fake()->optional()->randomElement([User::factory()]),
        ];
    }
}
