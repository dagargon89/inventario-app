<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseBin;
use Illuminate\Database\Seeder;

class InventoryMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = InventoryItem::all();
        $users = User::all();
        $warehouses = Warehouse::all();
        $bins = WarehouseBin::all();

        // Validar que existan bins y almacenes antes de continuar
        if ($warehouses->isEmpty() || $bins->isEmpty()) {
            return; // No hay almacenes o bins, no se pueden crear movimientos
        }

        // Crear movimientos de entrada (inbound)
        $this->createInboundMovements($items, $users, $warehouses, $bins);

        // Crear movimientos de salida (outbound)
        $this->createOutboundMovements($items, $users, $warehouses, $bins);

        // Crear ajustes de inventario
        $this->createAdjustmentMovements($items, $users, $warehouses, $bins);
    }

    private function createInboundMovements($items, $users, $warehouses, $bins): void
    {
        foreach ($items as $item) {
            $movementsCount = rand(2, 5);

            for ($i = 0; $i < $movementsCount; $i++) {
                $warehouse = $warehouses->random();
                $warehouseBins = $bins->where('warehouse_id', $warehouse->id);
                if ($warehouseBins->isEmpty()) {
                    continue; // Saltar si el almacén no tiene bins
                }
                $bin = $warehouseBins->random();
                $quantity = rand(10, 100);
                $unitCost = $this->getRealisticUnitCost($item);

                InventoryMovement::create([
                    'type' => 'inbound',
                    'quantity' => $quantity,
                    'quantity_before' => 0,
                    'quantity_after' => $quantity,
                    'unit_cost' => $unitCost,
                    'reason' => 'Compra inicial',
                    'reference_document' => 'PO-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'notes' => 'Entrada inicial de inventario',
                    'lot_number' => 'LOT-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                    'expires_at' => now()->addYears(rand(1, 5)),
                    'created_at' => now()->subDays(rand(30, 90)),
                    'inventory_item_id' => $item->id,
                    'warehouse_id' => $warehouse->id,
                    'warehouse_bin_id' => $bin->id,
                    'user_id' => $users->random()->id,
                ]);
            }
        }
    }

    private function createOutboundMovements($items, $users, $warehouses, $bins): void
    {
        foreach ($items as $item) {
            $movementsCount = rand(1, 3);

            for ($i = 0; $i < $movementsCount; $i++) {
                $warehouse = $warehouses->random();
                $warehouseBins = $bins->where('warehouse_id', $warehouse->id);
                if ($warehouseBins->isEmpty()) {
                    continue; // Saltar si el almacén no tiene bins
                }
                $bin = $warehouseBins->random();
                $quantity = rand(1, 20);

                InventoryMovement::create([
                    'type' => 'outbound',
                    'quantity' => $quantity,
                    'quantity_before' => $quantity + rand(5, 15),
                    'quantity_after' => rand(5, 15),
                    'unit_cost' => null,
                    'reason' => 'Venta/Consumo',
                    'reference_document' => 'SO-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'notes' => 'Salida por venta o consumo interno',
                    'lot_number' => null,
                    'expires_at' => null,
                    'created_at' => now()->subDays(rand(1, 30)),
                    'inventory_item_id' => $item->id,
                    'warehouse_id' => $warehouse->id,
                    'warehouse_bin_id' => $bin->id,
                    'user_id' => $users->random()->id,
                ]);
            }
        }
    }

    private function createAdjustmentMovements($items, $users, $warehouses, $bins): void
    {
        foreach ($items->random(min(10, $items->count())) as $item) { // Solo algunos artículos tienen ajustes
            $warehouse = $warehouses->random();
            $warehouseBins = $bins->where('warehouse_id', $warehouse->id);
            if ($warehouseBins->isEmpty()) {
                continue; // Saltar si el almacén no tiene bins
            }
            $bin = $warehouseBins->random();
            $quantityBefore = rand(10, 50);
            $adjustment = rand(-5, 5); // Ajuste positivo o negativo

            InventoryMovement::create([
                'type' => 'adjustment',
                'quantity' => abs($adjustment),
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityBefore + $adjustment,
                'unit_cost' => null,
                'reason' => $adjustment > 0 ? 'Ajuste por inventario físico' : 'Ajuste por pérdida',
                'reference_document' => 'ADJ-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'notes' => 'Ajuste de inventario por conteo físico',
                'lot_number' => null,
                'expires_at' => null,
                'created_at' => now()->subDays(rand(1, 15)),
                'inventory_item_id' => $item->id,
                'warehouse_id' => $warehouse->id,
                'warehouse_bin_id' => $bin->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }

    private function getRealisticUnitCost($item): float
    {
        $sku = $item->sku;

        if (str_starts_with($sku, 'LAP')) {
            return rand(15000, 25000); // Laptops
        } elseif (str_starts_with($sku, 'MON')) {
            return rand(3000, 8000); // Monitores
        } elseif (str_starts_with($sku, 'MED')) {
            return rand(500, 3000); // Equipos médicos
        } elseif (str_starts_with($sku, 'HERR')) {
            return rand(200, 2000); // Herramientas
        } elseif (str_starts_with($sku, 'CONS')) {
            return rand(50, 500); // Materiales de construcción
        } elseif (str_starts_with($sku, 'PAP')) {
            return rand(20, 200); // Papelería
        } else {
            return rand(100, 1000); // Otros
        }
    }
}
