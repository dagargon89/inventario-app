<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\InventoryItem;
use App\Models\InventoryStock;
use App\Models\WarehouseBin;

class InventoryStockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InventoryStock::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->randomFloat(4, 0, 99999999.9999),
            'quantity_reserved' => fake()->randomFloat(4, 0, 99999999.9999),
            'low_stock_threshold' => fake()->randomFloat(4, 0, 99999999.9999),
            'last_movement_at' => fake()->dateTime(),
            'inventory_item_id' => InventoryItem::factory(),
            'warehouse_bin_id' => WarehouseBin::factory(),
        ];
    }
}
