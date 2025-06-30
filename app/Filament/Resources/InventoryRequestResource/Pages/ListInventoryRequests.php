<?php

namespace App\Filament\Resources\InventoryRequestResource\Pages;

use App\Filament\Resources\InventoryRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryRequests extends ListRecords
{
    protected static string $resource = InventoryRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
