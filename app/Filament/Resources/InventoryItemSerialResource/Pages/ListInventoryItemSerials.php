<?php

namespace App\Filament\Resources\InventoryItemSerialResource\Pages;

use App\Filament\Resources\InventoryItemSerialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryItemSerials extends ListRecords
{
    protected static string $resource = InventoryItemSerialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
