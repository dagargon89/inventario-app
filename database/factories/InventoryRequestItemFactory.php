<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\InventoryItem;
use App\Models\InventoryRequest;
use App\Models\InventoryRequestItem;

class InventoryRequestItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InventoryRequestItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quantity_requested' => fake()->randomFloat(4, 0, 99999999.9999),
            'quantity_dispatched' => fake()->randomFloat(4, 0, 99999999.9999),
            'quantity_returned' => fake()->randomFloat(4, 0, 99999999.9999),
            'quantity_missing' => fake()->randomFloat(4, 0, 99999999.9999),
            'quantity_damaged' => fake()->randomFloat(4, 0, 99999999.9999),
            'notes' => fake()->text(),
            'inventory_request_id' => InventoryRequest::factory(),
            'inventory_item_id' => InventoryItem::factory(),
        ];
    }
}
