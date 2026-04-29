<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\PedidoResource\Pages;
use App\Models\OrdenPrestacion;
use App\Models\Pedido;
use App\Models\Prestador;
use App\Models\Secretaria;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class PedidoResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = Pedido::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Recepción y atención';

    protected static ?string $navigationLabel = 'Pedidos';

    protected static ?string $modelLabel = 'pedido';

    protected static ?string $pluralModelLabel = 'pedidos';

    protected static ?string $slug = 'pedidos';

    protected static ?int $navigationSort = 10;

    protected static ?string $panelScope = 'admin';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->whereIn('estado', ['pendiente', 'en_revision', 'observado'])->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del pedido')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('afiliado_id')
                        ->label('Afiliado')
                        ->options(fn () => User::query()
                            ->where(fn (Builder $query) => $query->where('role', 'afiliado')->orWhereNotNull('numero_afiliado'))
                            ->orderBy('name')
                            ->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('tipo')
                        ->label('Tipo')
                        ->options(self::tipos())
                        ->live()
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('secretaria_id', Pedido::secretariaSugeridaId($state)))
                        ->required()
                        ->native(false),

                    Forms\Components\Select::make('secretaria_id')
                        ->label('Área / secretaría')
                        ->options(fn () => Secretaria::query()->where('activa', true)->orderBy('orden')->pluck('nombre', 'id'))
                        ->searchable()
                        ->preload()
                        ->helperText('Recepción puede derivar el pedido al área que corresponda.'),

                    Forms\Components\Select::make('asignado_a')
                        ->label('Responsable')
                        ->options(fn () => User::query()
                            ->where(fn (Builder $query) => $query->whereNotNull('perfil_interno')->where('perfil_interno', '!=', 'ninguno'))
                            ->orderBy('name')
                            ->pluck('name', 'id'))
                        ->searchable()
                        ->preload(),

                    Forms\Components\Select::make('estado')
                        ->label('Estado')
                        ->options(self::estados())
                        ->required()
                        ->native(false),

                    Forms\Components\Textarea::make('descripcion')
                        ->label('Descripción')
                        ->rows(4)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('observaciones')
                        ->label('Observaciones internas')
                        ->helperText('Visible solo para el equipo admin.')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('observacion_afiliado')
                        ->label('Mensaje visible para el afiliado')
                        ->helperText('Usar para explicar qué falta o cuál fue la resolución.')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Documentación adjunta')
                ->columns(3)
                ->schema([
                    Forms\Components\FileUpload::make('archivo_dni')
                        ->label('DNI')
                        ->disk('local')
                        ->directory('pedidos')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(5120)
                        ->downloadable()
                        ->openable(),

                    Forms\Components\FileUpload::make('archivo_recibo')
                        ->label('Recibo de sueldo')
                        ->disk('local')
                        ->directory('pedidos')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(5120)
                        ->downloadable()
                        ->openable(),

                    Forms\Components\FileUpload::make('archivo_adicional')
                        ->label('Archivo adicional')
                        ->disk('local')
                        ->directory('pedidos')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(5120)
                        ->downloadable()
                        ->openable(),
                ]),

            Forms\Components\Section::make('Historial')
                ->collapsible()
                ->collapsed()
                ->visible(fn (?Pedido $record): bool => filled($record?->id))
                ->schema([
                    Forms\Components\Placeholder::make('movimientos')
                        ->label('')
                        ->content(fn (?Pedido $record): HtmlString => self::historialHtml($record)),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('N°')
                    ->formatStateUsing(fn (int $state): string => 'PED-'.str_pad($state, 6, '0', STR_PAD_LEFT))
                    ->sortable(),

                Tables\Columns\TextColumn::make('afiliado.name')
                    ->label('Afiliado')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('afiliado.numero_afiliado')
                    ->label('N° afiliado')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\BadgeColumn::make('tipo')
                    ->label('Tipo')
                    ->formatStateUsing(fn (string $state): string => self::tipos()[$state] ?? ucfirst($state))
                    ->colors(['primary' => fn () => true]),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => self::estados()[$state] ?? ucfirst($state))
                    ->color(fn (?string $state): string => Pedido::estadoColor($state)),

                Tables\Columns\TextColumn::make('secretaria.nombre')
                    ->label('Área')
                    ->placeholder('Sin derivar')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('asignadoA.name')
                    ->label('Responsable')
                    ->placeholder('Sin asignar')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options(self::tipos()),
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options(self::estados()),
                Tables\Filters\SelectFilter::make('secretaria_id')
                    ->label('Área')
                    ->relationship('secretaria', 'nombre'),
            ])
            ->actions([
                Tables\Actions\Action::make('emitir_orden')
                    ->label('Emitir orden')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('success')
                    ->visible(fn (Pedido $record): bool => in_array($record->estado, ['aprobado', 'en_revision', 'observado']))
                    ->form([
                        Forms\Components\Select::make('prestador_id')
                            ->label('Prestador')
                            ->options(fn () => Prestador::query()->where('activo', true)->orderBy('nombre')->pluck('nombre', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('tipo')
                            ->label('Tipo de orden')
                            ->options(OrdenPrestacion::tipos())
                            ->default(fn (Pedido $record): string => match ($record->tipo) {
                                'anteojos' => 'anteojos',
                                'medicacion', 'medicamentos' => 'medicacion',
                                'turismo' => 'turismo',
                                default => 'otro',
                            })
                            ->required()
                            ->native(false),
                        Forms\Components\Textarea::make('detalle')
                            ->label('Detalle para el prestador')
                            ->default(fn (Pedido $record): string => $record->descripcion)
                            ->required()
                            ->rows(3),
                        Forms\Components\Textarea::make('observaciones_internas')
                            ->label('Observaciones internas')
                            ->rows(2),
                    ])
                    ->action(function (Pedido $record, array $data): void {
                        $orden = OrdenPrestacion::create([
                            'prestador_id' => $data['prestador_id'],
                            'afiliado_id' => $record->afiliado_id,
                            'pedido_id' => $record->id,
                            'tipo' => $data['tipo'],
                            'detalle' => $data['detalle'],
                            'observaciones_internas' => $data['observaciones_internas'] ?? null,
                        ]);

                        if ($record->estado !== 'aprobado') {
                            $record->update(['estado' => 'aprobado', 'aprobado_at' => now()]);
                        }

                        self::notificarAfiliado($record, 'Orden emitida', 'ATSA emitió la orden '.$orden->codigo.' para tu pedido de '.self::tipos()[$record->tipo].'.', 'success');
                    }),

                Tables\Actions\Action::make('derivar')
                    ->label('Derivar')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('secretaria_id')
                            ->label('Área / secretaría')
                            ->options(fn () => Secretaria::query()->where('activa', true)->orderBy('orden')->pluck('nombre', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Textarea::make('observaciones')
                            ->label('Observación interna')
                            ->rows(3),
                    ])
                    ->action(function (Pedido $record, array $data): void {
                        $record->update([
                            'secretaria_id' => $data['secretaria_id'],
                            'derivado_por' => auth()->id(),
                            'estado' => $record->estado === 'pendiente' ? 'en_revision' : $record->estado,
                            'observaciones' => $data['observaciones'] ?? $record->observaciones,
                        ]);
                    }),

                Tables\Actions\Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Pedido $r) => ! in_array($r->estado, ['aprobado', 'entregado']))
                    ->requiresConfirmation()
                    ->modalHeading('Aprobar pedido')
                    ->modalDescription('El pedido pasará a estado Aprobado.')
                    ->action(function (Pedido $record): void {
                        $record->update(['estado' => 'aprobado', 'aprobado_at' => now()]);
                        self::notificarAfiliado($record, 'Pedido aprobado', 'Tu solicitud de '.self::tipos()[$record->tipo].' fue aprobada. Pronto nos contactaremos para coordinar la entrega.', 'success');
                    }),

                Tables\Actions\Action::make('en_revision')
                    ->label('En revisión')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn (Pedido $r) => $r->estado === 'pendiente')
                    ->action(fn (Pedido $r) => $r->update(['estado' => 'en_revision'])),

                Tables\Actions\Action::make('observar')
                    ->label('Falta documentación')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('warning')
                    ->visible(fn (Pedido $r) => ! in_array($r->estado, ['aprobado', 'rechazado', 'entregado']))
                    ->form([
                        Forms\Components\Textarea::make('observacion_afiliado')
                            ->label('Mensaje para el afiliado')
                            ->required()
                            ->rows(3),
                        Forms\Components\Textarea::make('observaciones')
                            ->label('Observación interna')
                            ->rows(2),
                    ])
                    ->action(function (Pedido $record, array $data): void {
                        $record->update([
                            'estado' => 'observado',
                            'observacion_afiliado' => $data['observacion_afiliado'],
                            'observaciones' => $data['observaciones'] ?? $record->observaciones,
                        ]);

                        self::notificarAfiliado($record, 'Pedido observado', $data['observacion_afiliado'], 'warning');
                    }),

                Tables\Actions\Action::make('entregar')
                    ->label('Entregado')
                    ->icon('heroicon-o-check-badge')
                    ->color('gray')
                    ->visible(fn (Pedido $r) => $r->estado === 'aprobado')
                    ->requiresConfirmation()
                    ->modalHeading('Marcar como entregado')
                    ->action(function (Pedido $record): void {
                        $record->update(['estado' => 'entregado', 'entregado_at' => now()]);
                        self::notificarAfiliado($record, 'Pedido entregado', 'Tu solicitud de '.self::tipos()[$record->tipo].' fue marcada como entregada.', 'success');
                    }),

                Tables\Actions\Action::make('rechazar')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Pedido $r) => ! in_array($r->estado, ['rechazado', 'entregado']))
                    ->form([
                        Forms\Components\Textarea::make('observaciones')
                            ->label('Motivo del rechazo')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Pedido $record, array $data): void {
                        $record->update(['estado' => 'rechazado', 'observaciones' => $data['observaciones']]);
                        self::notificarAfiliado($record, 'Pedido rechazado', 'Tu solicitud de '.self::tipos()[$record->tipo].' no pudo ser procesada. Motivo: '.$data['observaciones'], 'danger');
                    }),

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
            'index'  => Pages\ListPedidos::route('/'),
            'create' => Pages\CreatePedido::route('/create'),
            'edit'   => Pages\EditPedido::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['afiliado', 'secretaria', 'asignadoA']);
        $user = auth()->user();

        if ($user?->shouldScopeAdminWorkflowToSecretaria()) {
            $query->where('secretaria_id', $user->secretaria_id);
        }

        return $query;
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    private static function notificarAfiliado(Pedido $pedido, string $titulo, string $cuerpo, string $color): void
    {
        if (! $pedido->afiliado_id) {
            return;
        }

        $afiliado = User::find($pedido->afiliado_id);

        if (! $afiliado) {
            return;
        }

        Notification::make()
            ->title($titulo)
            ->body($cuerpo)
            ->color($color)
            ->sendToDatabase($afiliado);
    }

    private static function historialHtml(?Pedido $pedido): HtmlString
    {
        if (! $pedido?->exists) {
            return new HtmlString('<p class="text-sm text-gray-500">Sin movimientos todavía.</p>');
        }

        $items = $pedido->movimientos()
            ->with(['user', 'secretariaOrigen', 'secretariaDestino'])
            ->take(8)
            ->get();

        if ($items->isEmpty()) {
            return new HtmlString('<p class="text-sm text-gray-500">Sin movimientos todavía.</p>');
        }

        $html = $items->map(function ($movimiento): string {
            $fecha = e($movimiento->created_at?->format('d/m/Y H:i'));
            $usuario = e($movimiento->user?->name ?: 'Sistema');
            $estado = e(($movimiento->estado_anterior ?: 'sin estado').' -> '.($movimiento->estado_nuevo ?: 'sin cambios'));
            $area = e(($movimiento->secretariaOrigen?->nombre ?: 'Recepción').' -> '.($movimiento->secretariaDestino?->nombre ?: 'Sin área'));
            $nota = $movimiento->observacion_afiliado ? '<div class="text-xs text-gray-600">Afiliado: '.e($movimiento->observacion_afiliado).'</div>' : '';

            return "<li class=\"py-2\"><strong>{$fecha}</strong> - {$usuario}<br><span class=\"text-sm\">Estado: {$estado}</span><br><span class=\"text-sm\">Área: {$area}</span>{$nota}</li>";
        })->join('');

        return new HtmlString('<ul class="divide-y divide-gray-200">'.$html.'</ul>');
    }

    public static function tipos(): array
    {
        return [
            'subsidio'     => 'Subsidio',
            'bolson'       => 'Bolsón',
            'kit_escolar'  => 'Kit escolar',
            'nacimiento'   => 'Kit de nacimiento',
            'anteojos'     => 'Anteojos',
            'medicacion'   => 'Medicaciones',
            'turismo'      => 'Turismo',
            'ayuda_social' => 'Ayuda social',
            'tramite'      => 'Trámite',
            'otro'         => 'Otro',
        ];
    }

    public static function estados(): array
    {
        return Pedido::estados();
    }

    public static function estadoColor(?string $state): string
    {
        return Pedido::estadoColor($state);
    }

    public static function numero(int $id): string
    {
        return Pedido::numero($id);
    }
}
