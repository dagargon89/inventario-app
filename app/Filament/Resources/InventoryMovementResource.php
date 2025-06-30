<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryMovementResource\Pages;
use App\Filament\Resources\InventoryMovementResource\RelationManagers;
use App\Models\InventoryMovement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryMovementResource extends Resource
{
    protected static ?string $model = InventoryMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationGroup = 'Movimientos y Transacciones';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Movimiento de Inventario';

    protected static ?string $pluralModelLabel = 'Movimientos de Inventario';

    protected static ?string $navigationLabel = 'Movimientos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('Tipo de Movimiento')
                    ->options([
                        'inbound' => 'Entrada',
                        'outbound' => 'Salida',
                        'transfer' => 'Transferencia',
                        'adjustment' => 'Ajuste',
                    ])
                    ->required(),
                Forms\Components\Select::make('inventory_item_id')
                    ->label('Artículo')
                    ->relationship('inventoryItem', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('warehouse_id')
                    ->label('Almacén')
                    ->relationship('warehouse', 'name')
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
                    ->label('Cantidad')
                    ->numeric()
                    ->required()
                    ->step(0.0001),
                Forms\Components\TextInput::make('quantity_before')
                    ->label('Cantidad Anterior')
                    ->numeric()
                    ->required()
                    ->step(0.0001),
                Forms\Components\TextInput::make('quantity_after')
                    ->label('Cantidad Posterior')
                    ->numeric()
                    ->required()
                    ->step(0.0001),
                Forms\Components\TextInput::make('unit_cost')
                    ->label('Costo Unitario')
                    ->numeric()
                    ->step(0.01)
                    ->prefix('$'),
                Forms\Components\TextInput::make('reason')
                    ->label('Motivo')
                    ->maxLength(255),
                Forms\Components\TextInput::make('reference_document')
                    ->label('Documento de Referencia')
                    ->maxLength(255),
                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('lot_number')
                    ->label('Número de Lote')
                    ->maxLength(100),
                Forms\Components\DatePicker::make('expires_at')
                    ->label('Fecha de Vencimiento'),
                Forms\Components\Select::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DateTimePicker::make('created_at')
                    ->label('Fecha y Hora')
                    ->displayFormat('d/m/Y H:i')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipo')
                    ->colors([
                        'success' => 'inbound',
                        'danger' => 'outbound',
                        'warning' => 'transfer',
                        'info' => 'adjustment',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'inbound' => 'Entrada',
                        'outbound' => 'Salida',
                        'transfer' => 'Transferencia',
                        'adjustment' => 'Ajuste',
                    }),
                Tables\Columns\TextColumn::make('inventoryItem.name')
                    ->label('Artículo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventoryItem.sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('warehouse.name')
                    ->label('Almacén')
                    ->searchable(),
                Tables\Columns\TextColumn::make('warehouseBin.name')
                    ->label('Ubicación')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_cost')
                    ->label('Costo Unitario')
                    ->money('MXN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Motivo')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo de Movimiento')
                    ->options([
                        'inbound' => 'Entrada',
                        'outbound' => 'Salida',
                        'transfer' => 'Transferencia',
                        'adjustment' => 'Ajuste',
                    ]),
                Tables\Filters\SelectFilter::make('inventory_item_id')
                    ->label('Artículo')
                    ->relationship('inventoryItem', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('warehouse_id')
                    ->label('Almacén')
                    ->relationship('warehouse', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('created_at')
                    ->label('Fecha de Movimiento')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
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
            'index' => Pages\ListInventoryMovements::route('/'),
            'create' => Pages\CreateInventoryMovement::route('/create'),
            'edit' => Pages\EditInventoryMovement::route('/{record}/edit'),
        ];
    }
}
