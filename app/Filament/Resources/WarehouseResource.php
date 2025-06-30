<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseResource\Pages;
use App\Filament\Resources\WarehouseResource\RelationManagers;
use App\Models\Warehouse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Infraestructura';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Almacén';

    protected static ?string $pluralModelLabel = 'Almacenes';

    protected static ?string $navigationLabel = 'Almacenes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre del Almacén')
                    ->placeholder('Ej: Almacén Principal')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('location_description')
                    ->label('Descripción de Ubicación')
                    ->placeholder('Ej: Edificio A, Planta Baja, Zona Industrial Norte')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Almacén Activo')
                    ->default(true)
                    ->helperText('Indica si el almacén está en operación'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location_description')
                    ->label('Ubicación')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\BadgeColumn::make('is_active')
                    ->label('Estado')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Activo' : 'Inactivo'),
                Tables\Columns\TextColumn::make('warehouseBins_count')
                    ->label('Ubicaciones')
                    ->counts('warehouseBins')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->placeholder('Todos los almacenes')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos'),
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
            ->defaultSort('name');
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
            'index' => Pages\ListWarehouses::route('/'),
            'create' => Pages\CreateWarehouse::route('/create'),
            'edit' => Pages\EditWarehouse::route('/{record}/edit'),
        ];
    }
}
