<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryRequestResource\Pages;
use App\Filament\Resources\InventoryRequestResource\RelationManagers;
use App\Models\InventoryRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryRequestResource extends Resource
{
    protected static ?string $model = InventoryRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Movimientos y Transacciones';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Solicitud de Inventario';

    protected static ?string $pluralModelLabel = 'Solicitudes de Inventario';

    protected static ?string $navigationLabel = 'Solicitudes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('event_name')
                    ->label('Nombre del Evento')
                    ->placeholder('Ej: Conferencia Anual de Tecnología')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('event_date_start')
                    ->label('Fecha de Inicio del Evento')
                    ->required()
                    ->displayFormat('d/m/Y'),
                Forms\Components\DatePicker::make('event_date_end')
                    ->label('Fecha de Fin del Evento')
                    ->required()
                    ->displayFormat('d/m/Y'),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobada',
                        'dispatched' => 'Despachada',
                        'completed' => 'Completada',
                        'cancelled' => 'Cancelada',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\Textarea::make('notes_requester')
                    ->label('Notas del Solicitante')
                    ->placeholder('Detalles adicionales de la solicitud')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('notes_approver')
                    ->label('Notas del Aprobador')
                    ->placeholder('Comentarios del aprobador')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('approved_at')
                    ->label('Fecha de Aprobación')
                    ->displayFormat('d/m/Y H:i'),
                Forms\Components\DateTimePicker::make('dispatched_at')
                    ->label('Fecha de Despacho')
                    ->displayFormat('d/m/Y H:i'),
                Forms\Components\DateTimePicker::make('completed_at')
                    ->label('Fecha de Completado')
                    ->displayFormat('d/m/Y H:i'),
                Forms\Components\Select::make('user_id')
                    ->label('Solicitante')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('approved_by_id')
                    ->label('Aprobado Por')
                    ->relationship('approvedBy', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_name')
                    ->label('Evento')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_date_start')
                    ->label('Fecha Inicio')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_date_end')
                    ->label('Fecha Fin')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'info' => 'dispatched',
                        'primary' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobada',
                        'dispatched' => 'Despachada',
                        'completed' => 'Completada',
                        'cancelled' => 'Cancelada',
                    }),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Solicitante')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approvedBy.name')
                    ->label('Aprobado Por')
                    ->searchable(),
                Tables\Columns\TextColumn::make('inventoryRequestItems_count')
                    ->label('Artículos')
                    ->counts('inventoryRequestItems')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Solicitud')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobada',
                        'dispatched' => 'Despachada',
                        'completed' => 'Completada',
                        'cancelled' => 'Cancelada',
                    ]),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Solicitante')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('event_date_start')
                    ->label('Fecha del Evento')
                    ->form([
                        Forms\Components\DatePicker::make('event_from')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('event_until')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['event_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('event_date_start', '>=', $date),
                            )
                            ->when(
                                $data['event_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('event_date_end', '<=', $date),
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
            'index' => Pages\ListInventoryRequests::route('/'),
            'create' => Pages\CreateInventoryRequest::route('/create'),
            'edit' => Pages\EditInventoryRequest::route('/{record}/edit'),
        ];
    }
}
