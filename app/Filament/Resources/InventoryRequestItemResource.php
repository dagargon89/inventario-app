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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('quantity_requested')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('quantity_dispatched')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('quantity_returned')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('quantity_missing')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('quantity_damaged')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\Select::make('inventory_request_id')
                    ->relationship('inventoryRequest', 'id')
                    ->required(),
                Forms\Components\Select::make('inventory_item_id')
                    ->relationship('inventoryItem', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quantity_requested')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_dispatched')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_returned')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_missing')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_damaged')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventoryRequest.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventoryItem.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
