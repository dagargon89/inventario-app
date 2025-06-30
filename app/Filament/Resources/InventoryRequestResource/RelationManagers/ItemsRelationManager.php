<?php

namespace App\Filament\Resources\InventoryRequestResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'inventoryRequestItems';

    protected static ?string $recordTitleAttribute = 'inventory_item_id';

    protected static ?string $title = 'Artículos Solicitados';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('inventory_item_id')
                    ->label('Artículo')
                    ->relationship('inventoryItem', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            // Obtener información del artículo para mostrar stock disponible
                            $item = \App\Models\InventoryItem::find($state);
                            if ($item) {
                                $stock = $item->inventoryStocks()->sum('quantity');
                                $set('stock_disponible', $stock);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('stock_disponible')
                    ->label('Stock Disponible')
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Cantidad disponible en inventario'),
                Forms\Components\TextInput::make('quantity_requested')
                    ->label('Cantidad Solicitada')
                    ->numeric()
                    ->minValue(0.01)
                    ->step(0.01)
                    ->required()
                    ->helperText('Cantidad que necesitas para el evento'),
                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->placeholder('Detalles específicos sobre este artículo')
                    ->rows(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('inventoryItem.name')
            ->columns([
                Tables\Columns\TextColumn::make('inventoryItem.name')
                    ->label('Artículo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventoryItem.sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity_requested')
                    ->label('Cantidad Solicitada')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_dispatched')
                    ->label('Cantidad Despachada')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('quantity_returned')
                    ->label('Cantidad Devuelta')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notas')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Agregar Artículo')
                    ->modalHeading('Agregar Artículo a la Solicitud')
                    ->modalDescription('Selecciona el artículo y la cantidad que necesitas')
                    ->modalSubmitActionLabel('Agregar'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar'),
                Tables\Actions\DeleteAction::make()
                    ->label('Eliminar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Eliminar Seleccionados'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
