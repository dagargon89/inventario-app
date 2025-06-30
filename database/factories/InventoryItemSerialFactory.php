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
            'serial_number' => fake()->regexify('[A-Z]{2}[0-9]{8}'),
            'status' => fake()->randomElement(['in_stock', 'out_of_stock', 'reserved']),
            'inventory_item_id' => InventoryItem::factory(),
            'warehouse_bin_id' => WarehouseBin::factory(),
        ];
    }
}
