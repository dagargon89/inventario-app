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
            'type' => fake()->randomElement(['inbound', 'outbound', 'transfer', 'adjustment']),
            'quantity' => fake()->randomFloat(4, 1, 100),
            'quantity_before' => fake()->randomFloat(4, 0, 50),
            'quantity_after' => fake()->randomFloat(4, 0, 100),
            'unit_cost' => fake()->randomFloat(2, 10, 1000),
            'reason' => fake()->randomElement(['Compra', 'Venta', 'Ajuste', 'Transferencia']),
            'reference_document' => fake()->regexify('[A-Z]{2}-[0-9]{4}'),
            'notes' => fake()->sentence(),
            'lot_number' => fake()->regexify('LOT-[0-9]{3}'),
            'expires_at' => fake()->dateTimeBetween('now', '+2 years'),
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'inventory_item_id' => InventoryItem::factory(),
            'warehouse_id' => Warehouse::factory(),
            'warehouse_bin_id' => WarehouseBin::factory(),
            'user_id' => User::factory(),
        ];
    }
}
