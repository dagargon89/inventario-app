<?php

namespace App\Filament\Resources\InventoryRequestItemResource\Pages;

use App\Filament\Resources\InventoryRequestItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryRequestItems extends ListRecords
{
    protected static string $resource = InventoryRequestItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
