<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryItemSerialResource\Pages;
use App\Filament\Resources\InventoryItemSerialResource\RelationManagers;
use App\Models\InventoryItemSerial;
use App\Traits\HasSerialNumber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryItemSerialResource extends Resource
{
    use HasSerialNumber;

    protected static ?string $model = InventoryItemSerial::class;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $navigationGroup = 'Gestión de Inventario';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Número de Serie';

    protected static ?string $pluralModelLabel = 'Números de Serie';

    protected static ?string $navigationLabel = 'Números de Serie';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('serial_number')
                    ->label('Número de Serie')
                    ->placeholder('Se generará automáticamente')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Deja vacío para generar automáticamente')
                    ->disabled(),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'in_stock' => 'En Stock',
                        'out_of_stock' => 'Fuera de Stock',
                        'reserved' => 'Reservado',
                    ])
                    ->default('in_stock')
                    ->required(),
                Forms\Components\Select::make('inventory_item_id')
                    ->label('Artículo')
                    ->relationship('inventoryItem', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Artículo al que pertenece este número de serie'),
                Forms\Components\Select::make('warehouse_bin_id')
                    ->label('Ubicación')
                    ->relationship('warehouseBin', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('serial_number')
                    ->label('Número de Serie')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'success' => 'in_stock',
                        'danger' => 'out_of_stock',
                        'warning' => 'reserved',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'in_stock' => 'En Stock',
                        'out_of_stock' => 'Fuera de Stock',
                        'reserved' => 'Reservado',
                    }),
                Tables\Columns\TextColumn::make('inventoryItem.name')
                    ->label('Artículo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventoryItem.sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('warehouseBin.name')
                    ->label('Ubicación')
                    ->searchable(),
                Tables\Columns\TextColumn::make('warehouseBin.warehouse.name')
                    ->label('Almacén')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Registro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'in_stock' => 'En Stock',
                        'out_of_stock' => 'Fuera de Stock',
                        'reserved' => 'Reservado',
                    ]),
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
            'index' => Pages\ListInventoryItemSerials::route('/'),
            'create' => Pages\CreateInventoryItemSerial::route('/create'),
            'edit' => Pages\EditInventoryItemSerial::route('/{record}/edit'),
        ];
    }
}
