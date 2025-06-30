<?php

namespace App\Filament\Resources\InventoryRequestResource\Pages;

use App\Filament\Resources\InventoryRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateInventoryRequest extends CreateRecord
{
    protected static string $resource = InventoryRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Asignar automáticamente el usuario actual
        $data['user_id'] = Auth::id();

        // Si no es admin, establecer estado como pendiente
        if (Auth::id() !== 1) {
            $data['status'] = 'pending';
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
