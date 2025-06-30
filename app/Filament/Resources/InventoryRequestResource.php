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
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        // Por ahora, asumimos que el usuario con ID 1 es admin
        $isAdmin = $user->id === 1;
        $isSolicitante = !$isAdmin;

        return $form
            ->schema([
                Forms\Components\Section::make('Información del Evento')
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
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Detalles de la Solicitud')
                    ->schema([
                        Forms\Components\Textarea::make('notes_requester')
                            ->label('Notas del Solicitante')
                            ->placeholder('Detalles adicionales de la solicitud, justificación, etc.')
                            ->required()
                            ->rows(3),
                    ])
                    ->collapsible()
                    ->collapsed(),

                // Campos solo para administradores
                Forms\Components\Section::make('Gestión Administrativa')
                    ->schema([
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
                            ->required()
                            ->visible($isAdmin),
                        Forms\Components\Textarea::make('notes_approver')
                            ->label('Notas del Aprobador')
                            ->placeholder('Comentarios del aprobador')
                            ->rows(3)
                            ->visible($isAdmin),
                        Forms\Components\DateTimePicker::make('approved_at')
                            ->label('Fecha de Aprobación')
                            ->displayFormat('d/m/Y H:i')
                            ->visible($isAdmin),
                        Forms\Components\DateTimePicker::make('dispatched_at')
                            ->label('Fecha de Despacho')
                            ->displayFormat('d/m/Y H:i')
                            ->visible($isAdmin),
                        Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Fecha de Completado')
                            ->displayFormat('d/m/Y H:i')
                            ->visible($isAdmin),
                        Forms\Components\Select::make('approved_by_id')
                            ->label('Aprobado Por')
                            ->relationship('approvedBy', 'name')
                            ->searchable()
                            ->preload()
                            ->visible($isAdmin),
                    ])
                    ->columns(2)
                    ->visible($isAdmin)
                    ->collapsible()
                    ->collapsed(),

                // Campo de usuario (automático para solicitantes)
                Forms\Components\Select::make('user_id')
                    ->label('Solicitante')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->default($user->id)
                    ->disabled($isSolicitante)
                    ->visible($isAdmin),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();
        $isAdmin = $user->id === 1;

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
                    ->sortable()
                    ->visible($isAdmin),
                Tables\Columns\TextColumn::make('approvedBy.name')
                    ->label('Aprobado Por')
                    ->searchable()
                    ->visible($isAdmin),
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
                    ->preload()
                    ->visible($isAdmin),
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
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(function (Builder $query) use ($user, $isAdmin) {
                // Si no es admin, solo mostrar sus propias solicitudes
                if (!$isAdmin) {
                    $query->where('user_id', $user->id);
                }
                return $query;
            });
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryRequests::route('/'),
            'create' => Pages\CreateInventoryRequest::route('/create'),
            'view' => Pages\ViewInventoryRequest::route('/{record}'),
            'edit' => Pages\EditInventoryRequest::route('/{record}/edit'),
        ];
    }
}
