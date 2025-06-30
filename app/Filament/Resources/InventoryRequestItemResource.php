<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryRequestItemResource\Pages;
use App\Filament\Resources\InventoryRequestItemResource\RelationManagers;
use App\Models\InventoryRequestItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryRequestItemResource extends Resource
{
    protected static ?string $model = InventoryRequestItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Movimientos y Transacciones';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Item de Solicitud';

    protected static ?string $pluralModelLabel = 'Items de Solicitud';

    protected static ?string $navigationLabel = 'Items de Solicitud';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('inventory_request_id')
                    ->label('Solicitud')
                    ->relationship('inventoryRequest', 'event_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('inventory_item_id')
                    ->label('Artículo')
                    ->relationship('inventoryItem', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('quantity_requested')
                    ->label('Cantidad Solicitada')
                    ->numeric()
                    ->required()
                    ->step(0.0001)
                    ->minValue(0),
                Forms\Components\TextInput::make('quantity_dispatched')
                    ->label('Cantidad Despachada')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->step(0.0001)
                    ->minValue(0),
                Forms\Components\TextInput::make('quantity_returned')
                    ->label('Cantidad Devuelta')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->step(0.0001)
                    ->minValue(0),
                Forms\Components\TextInput::make('quantity_missing')
                    ->label('Cantidad Faltante')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->step(0.0001)
                    ->minValue(0),
                Forms\Components\TextInput::make('quantity_damaged')
                    ->label('Cantidad Dañada')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->step(0.0001)
                    ->minValue(0),
                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->placeholder('Observaciones sobre este item')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('inventoryRequest.event_name')
                    ->label('Evento')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventoryRequest.status')
                    ->label('Estado Solicitud')
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
                Tables\Columns\TextColumn::make('inventoryItem.name')
                    ->label('Artículo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventoryItem.sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity_requested')
                    ->label('Solicitado')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_dispatched')
                    ->label('Despachado')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_returned')
                    ->label('Devuelto')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_missing')
                    ->label('Faltante')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->color('danger')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_damaged')
                    ->label('Dañado')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->color('warning')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('inventory_request_id')
                    ->label('Solicitud')
                    ->relationship('inventoryRequest', 'event_name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('inventory_item_id')
                    ->label('Artículo')
                    ->relationship('inventoryItem', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('has_missing')
                    ->label('Con Faltantes')
                    ->query(fn (Builder $query): Builder => $query->where('quantity_missing', '>', 0)),
                Tables\Filters\Filter::make('has_damaged')
                    ->label('Con Dañados')
                    ->query(fn (Builder $query): Builder => $query->where('quantity_damaged', '>', 0)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Ver'),
                Tables\Actions\EditAction::make()
                    ->label('Editar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Eliminar Seleccionados'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryRequestItems::route('/'),
            'create' => Pages\CreateInventoryRequestItem::route('/create'),
            'edit' => Pages\EditInventoryRequestItem::route('/{record}/edit'),
        ];
    }
}
