<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\InventoryItem;

class InventoryItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InventoryItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sku' => fake()->regexify('[A-Za-z0-9]{100}'),
            'name' => fake()->name(),
            'description' => fake()->text(),
            'unit_of_measure' => fake()->regexify('[A-Za-z0-9]{50}'),
            'status' => fake()->randomElement(["['active'",""]),
            'tracking_type' => fake()->randomElement(["['quantity'",""]),
            'attributes' => '{}',
        ];
    }
}
