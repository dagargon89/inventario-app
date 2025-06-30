<?php

namespace App\Filament\Resources\InventoryItemSerialResource\Pages;

use App\Filament\Resources\InventoryItemSerialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryItemSerial extends EditRecord
{
    protected static string $resource = InventoryItemSerialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
