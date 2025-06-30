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
            'event_name' => fake()->word(),
            'event_date_start' => fake()->date(),
            'event_date_end' => fake()->date(),
            'status' => fake()->randomElement(["['pending'",""]),
            'notes_requester' => fake()->text(),
            'notes_approver' => fake()->text(),
            'approved_at' => fake()->dateTime(),
            'dispatched_at' => fake()->dateTime(),
            'completed_at' => fake()->dateTime(),
            'user_id' => User::factory(),
            'approved_by:nullable_id' => User::factory(),
        ];
    }
}
