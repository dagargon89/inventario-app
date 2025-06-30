<?php

namespace App\Filament\Resources\InventoryStockResource\Pages;

use App\Filament\Resources\InventoryStockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryStocks extends ListRecords
{
    protected static string $resource = InventoryStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
