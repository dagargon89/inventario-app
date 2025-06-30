<?php

namespace App\Filament\Resources\InventoryRequestResource\Pages;

use App\Filament\Resources\InventoryRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewInventoryRequest extends ViewRecord
{
    protected static string $resource = InventoryRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Editar Solicitud'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información del Evento')
                    ->schema([
                        Infolists\Components\TextEntry::make('event_name')
                            ->label('Nombre del Evento'),
                        Infolists\Components\TextEntry::make('event_date_start')
                            ->label('Fecha de Inicio')
                            ->date('d/m/Y'),
                        Infolists\Components\TextEntry::make('event_date_end')
                            ->label('Fecha de Fin')
                            ->date('d/m/Y'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Detalles de la Solicitud')
                    ->schema([
                        Infolists\Components\TextEntry::make('notes_requester')
                            ->label('Notas del Solicitante')
                            ->markdown(),
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Solicitante'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Fecha de Solicitud')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Infolists\Components\Section::make('Estado de la Solicitud')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->label('Estado')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'approved' => 'success',
                                'dispatched' => 'info',
                                'completed' => 'primary',
                                'cancelled' => 'danger',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pending' => 'Pendiente',
                                'approved' => 'Aprobada',
                                'dispatched' => 'Despachada',
                                'completed' => 'Completada',
                                'cancelled' => 'Cancelada',
                            }),
                        Infolists\Components\TextEntry::make('notes_approver')
                            ->label('Notas del Aprobador')
                            ->markdown()
                            ->visible(fn ($record) => $record->notes_approver),
                        Infolists\Components\TextEntry::make('approvedBy.name')
                            ->label('Aprobado Por')
                            ->visible(fn ($record) => $record->approvedBy),
                        Infolists\Components\TextEntry::make('approved_at')
                            ->label('Fecha de Aprobación')
                            ->dateTime('d/m/Y H:i')
                            ->visible(fn ($record) => $record->approved_at),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
