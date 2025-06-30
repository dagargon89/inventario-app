<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\InventoryItem;
use App\Models\InventoryItemSerial;
use App\Models\WarehouseBin;

class InventoryItemSerialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InventoryItemSerial::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'serial_number' => fake()->word(),
            'status' => fake()->randomElement(["['in_stock'",""]),
            'inventory_item_id' => InventoryItem::factory(),
            'nullable_id' => WarehouseBin::factory(),
        ];
    }
}
