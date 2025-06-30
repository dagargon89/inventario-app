<?php

namespace App\Filament\Resources\InventoryItemSerialResource\Pages;

use App\Filament\Resources\InventoryItemSerialResource;
use App\Traits\HasSerialNumber;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInventoryItemSerial extends CreateRecord
{
    use HasSerialNumber;

    protected static string $resource = InventoryItemSerialResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generar número de serie automáticamente
        $data['serial_number'] = $this->generateUniqueSerialNumber($data['inventory_item_id']);

        return $data;
    }
}
