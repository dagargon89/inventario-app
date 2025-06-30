<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuarios de prueba
        $this->call([
            UserSeeder::class,
            SupplierSeeder::class,
            WarehouseSeeder::class,
            InventoryItemSeeder::class,
            InventoryStockSeeder::class,
            InventoryMovementSeeder::class,
            InventoryRequestSeeder::class,
        ]);
    }
}
