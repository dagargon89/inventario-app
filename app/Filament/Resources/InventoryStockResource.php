<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryStockResource\Pages;
use App\Filament\Resources\InventoryStockResource\RelationManagers;
use App\Models\InventoryStock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryStockResource extends Resource
{
    protected static ?string $model = InventoryStock::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Gestión de Inventario';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Stock de Inventario';

    protected static ?string $pluralModelLabel = 'Stock de Inventario';

    protected static ?string $navigationLabel = 'Stock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('inventory_item_id')
                    ->label('Artículo')
                    ->relationship('inventoryItem', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('warehouse_bin_id')
                    ->label('Ubicación')
                    ->relationship('warehouseBin', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Cantidad Disponible')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->step(0.0001),
                Forms\Components\TextInput::make('quantity_reserved')
                    ->label('Cantidad Reservada')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->step(0.0001),
                Forms\Components\TextInput::make('low_stock_threshold')
                    ->label('Umbral de Stock Bajo')
                    ->numeric()
                    ->step(0.0001)
                    ->helperText('Cantidad mínima antes de generar alerta'),
                Forms\Components\DateTimePicker::make('last_movement_at')
                    ->label('Último Movimiento')
                    ->displayFormat('d/m/Y H:i'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('inventoryItem.name')
                    ->label('Artículo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventoryItem.sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('warehouseBin.name')
                    ->label('Ubicación')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('warehouseBin.warehouse.name')
                    ->label('Almacén')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Disponible')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_reserved')
                    ->label('Reservado')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('available_quantity')
                    ->label('Disponible Real')
                    ->getStateUsing(fn ($record) => $record->quantity - $record->quantity_reserved)
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->color(fn ($record) => ($record->quantity - $record->quantity_reserved) <= ($record->low_stock_threshold ?? 0) ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('last_movement_at')
                    ->label('Último Movimiento')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('inventory_item_id')
                    ->label('Artículo')
                    ->relationship('inventoryItem', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('warehouse_bin_id')
                    ->label('Ubicación')
                    ->relationship('warehouseBin', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar'),
                Tables\Actions\ViewAction::make()
                    ->label('Ver'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Eliminar Seleccionados'),
                ]),
            ])
            ->defaultSort('inventoryItem.name');
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
            'index' => Pages\ListInventoryStocks::route('/'),
            'create' => Pages\CreateInventoryStock::route('/create'),
            'edit' => Pages\EditInventoryStock::route('/{record}/edit'),
        ];
    }
}
