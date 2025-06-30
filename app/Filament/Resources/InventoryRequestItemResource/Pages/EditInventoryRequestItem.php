<?php

namespace App\Filament\Resources\InventoryRequestItemResource\Pages;

use App\Filament\Resources\InventoryRequestItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryRequestItem extends EditRecord
{
    protected static string $resource = InventoryRequestItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
