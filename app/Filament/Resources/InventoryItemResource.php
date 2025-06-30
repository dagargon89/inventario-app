<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryItemResource\Pages;
use App\Filament\Resources\InventoryItemResource\RelationManagers;
use App\Models\InventoryItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryItemResource extends Resource
{
    protected static ?string $model = InventoryItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Gestión de Inventario';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Artículo de Inventario';

    protected static ?string $pluralModelLabel = 'Artículos de Inventario';

    protected static ?string $navigationLabel = 'Artículos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->placeholder('Ej: LAP-001')
                    ->required()
                    ->maxLength(100)
                    ->helperText('Código único de identificación del artículo'),
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->placeholder('Ej: Laptop Dell Inspiron 15')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->placeholder('Descripción detallada del artículo')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('unit_of_measure')
                    ->label('Unidad de Medida')
                    ->placeholder('Ej: Unidad, Pieza, Caja')
                    ->required()
                    ->maxLength(50),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'active' => 'Activo',
                        'inactive' => 'Inactivo',
                    ])
                    ->default('active')
                    ->required(),
                Forms\Components\Select::make('tracking_type')
                    ->label('Tipo de Seguimiento')
                    ->options([
                        'quantity' => 'Por Cantidad',
                        'serial' => 'Por Número de Serie',
                    ])
                    ->default('quantity')
                    ->required()
                    ->helperText('Define cómo se rastrea este artículo en el inventario'),
                Forms\Components\KeyValue::make('attributes')
                    ->label('Atributos Adicionales')
                    ->keyLabel('Atributo')
                    ->valueLabel('Valor')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_of_measure')
                    ->label('Unidad')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Activo',
                        'inactive' => 'Inactivo',
                    }),
                Tables\Columns\BadgeColumn::make('tracking_type')
                    ->label('Seguimiento')
                    ->colors([
                        'primary' => 'quantity',
                        'warning' => 'serial',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'quantity' => 'Por Cantidad',
                        'serial' => 'Por Serie',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última Actualización')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'active' => 'Activo',
                        'inactive' => 'Inactivo',
                    ]),
                Tables\Filters\SelectFilter::make('tracking_type')
                    ->label('Tipo de Seguimiento')
                    ->options([
                        'quantity' => 'Por Cantidad',
                        'serial' => 'Por Número de Serie',
                    ]),
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
            'index' => Pages\ListInventoryItems::route('/'),
            'create' => Pages\CreateInventoryItem::route('/create'),
            'edit' => Pages\EditInventoryItem::route('/{record}/edit'),
        ];
    }
}
