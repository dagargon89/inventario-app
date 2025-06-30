<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseBinResource\Pages;
use App\Filament\Resources\WarehouseBinResource\RelationManagers;
use App\Models\WarehouseBin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WarehouseBinResource extends Resource
{
    protected static ?string $model = WarehouseBin::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Infraestructura';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Ubicación';

    protected static ?string $pluralModelLabel = 'Ubicaciones';

    protected static ?string $navigationLabel = 'Ubicaciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Código de Ubicación')
                    ->placeholder('Ej: A-01-01')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Código único para identificar la ubicación'),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->placeholder('Ej: Estante A, Nivel 1, Posición 1 - Equipos Electrónicos')
                    ->columnSpanFull(),
                Forms\Components\Select::make('warehouse_id')
                    ->label('Almacén')
                    ->relationship('warehouse', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('warehouse.name')
                    ->label('Almacén')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventoryStocks_count')
                    ->label('Artículos')
                    ->counts('inventoryStocks')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('warehouse_id')
                    ->label('Almacén')
                    ->relationship('warehouse', 'name')
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
            'index' => Pages\ListWarehouseBins::route('/'),
            'create' => Pages\CreateWarehouseBin::route('/create'),
            'edit' => Pages\EditWarehouseBin::route('/{record}/edit'),
        ];
    }
}
