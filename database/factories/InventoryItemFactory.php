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
            'sku' => fake()->regexify('[A-Z]{3}-[0-9]{3}'),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'unit_of_measure' => fake()->randomElement(['Unidad', 'Pieza', 'Caja', 'Set', 'Resma', 'Kg', 'Litro']),
            'status' => fake()->randomElement(['active', 'inactive']),
            'tracking_type' => fake()->randomElement(['quantity', 'serial']),
            'attributes' => json_encode([
                'marca' => fake()->company(),
                'modelo' => fake()->regexify('[A-Z]{2}[0-9]{3}'),
            ]),
        ];
    }
}
