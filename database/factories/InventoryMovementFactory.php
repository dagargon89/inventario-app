<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseBin;

class InventoryMovementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InventoryMovement::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(["['inbound'",""]),
            'quantity' => fake()->randomFloat(4, 0, 99999999.9999),
            'quantity_before' => fake()->randomFloat(4, 0, 99999999.9999),
            'quantity_after' => fake()->randomFloat(4, 0, 99999999.9999),
            'unit_cost' => fake()->randomFloat(4, 0, 99999999.9999),
            'reason' => fake()->word(),
            'reference_document' => fake()->word(),
            'notes' => fake()->text(),
            'lot_number' => fake()->regexify('[A-Za-z0-9]{100}'),
            'expires_at' => fake()->date(),
            'created_at' => fake()->dateTime(),
            'inventory_item_id' => InventoryItem::factory(),
            'warehouse_id' => Warehouse::factory(),
            'warehouse_bin_id' => WarehouseBin::factory(),
            'nullable_id' => User::factory(),
        ];
    }
}
