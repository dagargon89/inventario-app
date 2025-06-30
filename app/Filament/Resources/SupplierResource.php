<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Administración';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Proveedor';

    protected static ?string $pluralModelLabel = 'Proveedores';

    protected static ?string $navigationLabel = 'Proveedores';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre del Proveedor')
                    ->placeholder('Ej: Tecnología Avanzada S.A.')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_person')
                    ->label('Persona de Contacto')
                    ->placeholder('Ej: Ing. Roberto Martínez')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->placeholder('Ej: ventas@proveedor.com')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Teléfono')
                    ->tel()
                    ->placeholder('Ej: +52 55 1234 5678')
                    ->maxLength(255),
                Forms\Components\Textarea::make('address')
                    ->label('Dirección')
                    ->placeholder('Ej: Av. Insurgentes Sur 1234, Col. Del Valle, CDMX')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->placeholder('Información adicional sobre el proveedor')
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('contact_person')
                    ->label('Contacto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('inventoryItems_count')
                    ->label('Artículos')
                    ->counts('inventoryItems')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Registro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_items')
                    ->label('Con Artículos')
                    ->query(fn (Builder $query): Builder => $query->whereHas('inventoryItems')),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
