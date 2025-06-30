<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\InventoryStock;
use App\Models\WarehouseBin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventoryStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = InventoryItem::all();
        $bins = WarehouseBin::all();

        // Distribuir stock de manera realista
        foreach ($items as $item) {
            // Seleccionar 1-3 ubicaciones aleatorias para cada artículo
            $selectedBins = $bins->random(rand(1, 3));

            foreach ($selectedBins as $bin) {
                $quantity = $this->getRealisticQuantity($item);

                InventoryStock::create([
                    'quantity' => $quantity,
                    'quantity_reserved' => rand(0, $quantity * 0.3), // 0-30% reservado
                    'low_stock_threshold' => $quantity * 0.2, // 20% del stock como umbral bajo
                    'last_movement_at' => now()->subDays(rand(1, 30)),
                    'inventory_item_id' => $item->id,
                    'warehouse_bin_id' => $bin->id,
                ]);
            }
        }
    }

    private function getRealisticQuantity($item): float
    {
        // Cantidades realistas según el tipo de artículo
        $sku = $item->sku;

        if (str_starts_with($sku, 'LAP') || str_starts_with($sku, 'MON') || str_starts_with($sku, 'MED')) {
            return rand(5, 25); // Equipos electrónicos y médicos
        } elseif (str_starts_with($sku, 'HERR')) {
            return rand(10, 50); // Herramientas
        } elseif (str_starts_with($sku, 'CONS')) {
            return rand(100, 1000); // Materiales de construcción
        } elseif (str_starts_with($sku, 'PAP')) {
            return rand(20, 200); // Papelería
        } else {
            return rand(10, 100); // Otros artículos
        }
    }
}
