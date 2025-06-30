<?php

namespace App\Filament\Resources\InventoryRequestResource\Pages;

use App\Filament\Resources\InventoryRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryRequest extends EditRecord
{
    protected static string $resource = InventoryRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
