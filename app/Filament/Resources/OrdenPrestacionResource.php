<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\OrdenPrestacionResource\Pages;
use App\Models\OrdenPrestacion;
use App\Models\Pedido;
use App\Models\Prestador;
use App\Models\SolicitudBeneficio;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrdenPrestacionResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = OrdenPrestacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Atención al afiliado';

    protected static ?string $navigationLabel = 'Órdenes a prestadores';

    protected static ?string $modelLabel = 'orden de prestación';

    protected static ?string $pluralModelLabel = 'órdenes de prestación';

    protected static ?string $slug = 'ordenes-prestacion';

    protected static ?int $navigationSort = 7;

    protected static ?string $panelScope = 'admin';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->whereIn('estado', ['emitida', 'aceptada', 'observada'])->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Orden')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('codigo')
                        ->label('Código')
                        ->disabled()
                        ->dehydrated(false),

                    Forms\Components\Select::make('estado')
                        ->label('Estado')
                        ->options(OrdenPrestacion::estados())
                        ->required()
                        ->native(false),

                    Forms\Components\Select::make('prestador_id')
                        ->label('Prestador')
                        ->options(fn () => Prestador::query()->where('activo', true)->orderBy('nombre')->pluck('nombre', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('tipo')
                        ->label('Tipo')
                        ->options(OrdenPrestacion::tipos())
                        ->required()
                        ->native(false),

                    Forms\Components\Select::make('afiliado_id')
                        ->label('Afiliado')
                        ->options(fn () => User::query()
                            ->where(fn (Builder $query) => $query->where('role', 'afiliado')->orWhereNotNull('numero_afiliado'))
                            ->orderBy('name')
                            ->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('pedido_id')
                        ->label('Pedido vinculado')
                        ->options(fn () => Pedido::query()
                            ->latest()
                            ->limit(100)
                            ->get()
                            ->mapWithKeys(fn (Pedido $pedido): array => [$pedido->id => Pedido::numero($pedido->id).' - '.$pedido->afiliado?->name]))
                        ->searchable(),

                    Forms\Components\Select::make('solicitud_beneficio_id')
                        ->label('Solicitud de beneficio vinculada')
                        ->options(fn () => SolicitudBeneficio::query()
                            ->with(['beneficio', 'afiliado'])
                            ->latest()
                            ->limit(100)
                            ->get()
                            ->mapWithKeys(fn (SolicitudBeneficio $solicitud): array => [$solicitud->id => SolicitudBeneficio::numero($solicitud->id).' - '.$solicitud->beneficio?->titulo.' - '.$solicitud->afiliado?->name]))
                        ->searchable(),

                    Forms\Components\Textarea::make('detalle')
                        ->label('Detalle para el prestador')
                        ->rows(4)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('observaciones_internas')
                        ->label('Observaciones internas')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('respuesta_prestador')
                        ->label('Respuesta / informe del prestador')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('afiliado.name')
                    ->label('Afiliado')
                    ->searchable(),

                Tables\Columns\TextColumn::make('prestador.nombre')
                    ->label('Prestador')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tipo')
                    ->label('Tipo')
                    ->formatStateUsing(fn (?string $state): string => OrdenPrestacion::tipos()[$state] ?? ucfirst((string) $state))
                    ->color('info'),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(fn (?string $state): string => OrdenPrestacion::estados()[$state] ?? ucfirst((string) $state))
                    ->color(fn (?string $state): string => OrdenPrestacion::estadoColor($state)),

                Tables\Columns\TextColumn::make('emitida_at')
                    ->label('Emitida')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('entregada_at')
                    ->label('Entregada')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('respuesta_prestador')
                    ->label('Informe')
                    ->limit(45)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('emitidaPor.name')
                    ->label('Emitida por')
                    ->placeholder('Sistema')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('cerradaPor.name')
                    ->label('Cerrada por')
                    ->placeholder('Prestador')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options(OrdenPrestacion::estados()),
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options(OrdenPrestacion::tipos()),
                Tables\Filters\SelectFilter::make('prestador_id')
                    ->label('Prestador')
                    ->relationship('prestador', 'nombre'),
                Tables\Filters\Filter::make('pendientes_entrega')
                    ->label('Pendientes de entrega')
                    ->query(fn (Builder $query): Builder => $query->whereIn('estado', ['emitida', 'aceptada', 'observada'])),
                Tables\Filters\Filter::make('entregadas_hoy')
                    ->label('Entregadas hoy')
                    ->query(fn (Builder $query): Builder => $query->whereDate('entregada_at', now()->toDateString())),
            ])
            ->actions([
                Tables\Actions\Action::make('aceptar')
                    ->label('Aceptada')
                    ->icon('heroicon-o-hand-thumb-up')
                    ->color('info')
                    ->visible(fn (OrdenPrestacion $record): bool => $record->estado === 'emitida')
                    ->action(fn (OrdenPrestacion $record) => $record->update(['estado' => 'aceptada', 'aceptada_at' => now()])),

                Tables\Actions\Action::make('entregar')
                    ->label('Entregada')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (OrdenPrestacion $record): bool => in_array($record->estado, ['emitida', 'aceptada', 'observada']))
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('respuesta_prestador')
                            ->label('Informe de entrega')
                            ->rows(3),
                    ])
                    ->action(function (OrdenPrestacion $record, array $data): void {
                        $record->registrarEntrega($data['respuesta_prestador'] ?? null, auth()->id());
                    }),

                Tables\Actions\Action::make('anular')
                    ->label('Anular')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (OrdenPrestacion $record): bool => $record->estado !== 'anulada')
                    ->requiresConfirmation()
                    ->action(fn (OrdenPrestacion $record) => $record->update(['estado' => 'anulada', 'cerrada_por' => auth()->id()])),

                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrdenPrestacions::route('/'),
            'create' => Pages\CreateOrdenPrestacion::route('/create'),
            'edit' => Pages\EditOrdenPrestacion::route('/{record}/edit'),
        ];
    }
}
