<?php

namespace App\Traits;

use App\Models\InventoryItemSerial;

trait HasSerialNumber
{
    /**
     * Genera un número de serie único basado en el SKU del artículo
     */
    public static function generateUniqueSerialNumber(int $inventoryItemId): string
    {
        // Obtener el artículo para usar su SKU como prefijo
        $item = \App\Models\InventoryItem::find($inventoryItemId);
        $prefix = $item ? strtoupper(substr($item->sku, 0, 3)) : 'SN';

        // Contar cuántos números de serie existen para este artículo
        $count = InventoryItemSerial::where('inventory_item_id', $inventoryItemId)->count();

        // Generar número con formato: PREFIJO + AÑO + CONTADOR (ej: LAP2024001)
        $year = date('Y');
        $counter = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        $serialNumber = "{$prefix}{$year}{$counter}";

        // Verificar que sea único, si no, agregar sufijo
        $maxAttempts = 100;
        $attempt = 0;

        while (InventoryItemSerial::where('serial_number', $serialNumber)->exists() && $attempt < $maxAttempts) {
            $attempt++;
            $suffix = strtoupper(substr(md5(uniqid()), 0, 4));
            $serialNumber = "{$prefix}{$year}{$counter}-{$suffix}";
        }

        return $serialNumber;
    }
}
