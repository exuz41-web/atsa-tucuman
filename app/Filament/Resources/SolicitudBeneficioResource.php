<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\SolicitudBeneficioResource\Pages;
use App\Models\Beneficio;
use App\Models\Secretaria;
use App\Models\SolicitudBeneficio;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class SolicitudBeneficioResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = SolicitudBeneficio::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Afiliados';

    protected static ?string $navigationLabel = 'Solicitudes de beneficios';

    protected static ?string $modelLabel = 'solicitud de beneficio';

    protected static ?string $pluralModelLabel = 'solicitudes de beneficios';

    protected static ?string $slug = 'solicitudes-beneficios';

    protected static ?int $navigationSort = 2;

    protected static ?string $panelScope = 'admin';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->whereIn('estado', ['pendiente', 'en_revision', 'observada'])->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Solicitud')
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

                    Forms\Components\Select::make('beneficio_id')
                        ->label('Beneficio')
                        ->options(fn () => Beneficio::query()
                            ->orderBy('titulo')
                            ->pluck('titulo', 'id'))
                        ->live()
                        ->afterStateUpdated(function (Forms\Set $set, ?int $state): void {
                            $categoria = $state ? Beneficio::whereKey($state)->value('categoria') : null;
                            $set('secretaria_id', SolicitudBeneficio::secretariaSugeridaId($categoria));
                        })
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('secretaria_id')
                        ->label('Área / secretaría')
                        ->options(fn () => Secretaria::query()->where('activa', true)->orderBy('orden')->pluck('nombre', 'id'))
                        ->searchable()
                        ->preload()
                        ->helperText('Recepción puede derivar la solicitud al área que corresponda.'),

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
                        ->options(SolicitudBeneficio::estados())
                        ->required()
                        ->native(false),

                    Forms\Components\DateTimePicker::make('respondido_at')
                        ->label('Fecha de respuesta')
                        ->disabled(),

                    Forms\Components\Textarea::make('mensaje')
                        ->label('Mensaje del afiliado')
                        ->rows(4)
                        ->disabled()
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('observaciones')
                        ->label('Observaciones internas')
                        ->helperText('Notas del equipo admin.')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('observacion_afiliado')
                        ->label('Mensaje visible para el afiliado')
                        ->helperText('Usar para explicar qué falta o cuál fue la resolución.')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Documentación')
                ->columns(3)
                ->schema([
                    Forms\Components\FileUpload::make('archivo_dni')
                        ->label('DNI')
                        ->disk('local')
                        ->directory('solicitudes-beneficios')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                        ->downloadable()
                        ->openable(),

                    Forms\Components\FileUpload::make('archivo_recibo')
                        ->label('Recibo de sueldo')
                        ->disk('local')
                        ->directory('solicitudes-beneficios')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                        ->downloadable()
                        ->openable(),

                    Forms\Components\FileUpload::make('archivo_adicional')
                        ->label('Adicional')
                        ->disk('local')
                        ->directory('solicitudes-beneficios')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                        ->downloadable()
                        ->openable(),
                ]),

            Forms\Components\Section::make('Historial')
                ->collapsible()
                ->collapsed()
                ->visible(fn (?SolicitudBeneficio $record): bool => filled($record?->id))
                ->schema([
                    Forms\Components\Placeholder::make('movimientos')
                        ->label('')
                        ->content(fn (?SolicitudBeneficio $record): HtmlString => self::historialHtml($record)),
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
                    ->formatStateUsing(fn (int $state): string => SolicitudBeneficio::numero($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('afiliado.name')
                    ->label('Afiliado')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('beneficio.titulo')
                    ->label('Beneficio')
                    ->limit(35)
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => SolicitudBeneficio::estados()[$state] ?? ucfirst($state))
                    ->color(fn (?string $state): string => SolicitudBeneficio::estadoColor($state)),

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
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options(SolicitudBeneficio::estados()),
                Tables\Filters\SelectFilter::make('beneficio_id')
                    ->label('Beneficio')
                    ->relationship('beneficio', 'titulo'),
                Tables\Filters\SelectFilter::make('secretaria_id')
                    ->label('Área')
                    ->relationship('secretaria', 'nombre'),
            ])
            ->actions([
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
                    ->action(function (SolicitudBeneficio $record, array $data): void {
                        $record->update([
                            'secretaria_id' => $data['secretaria_id'],
                            'derivado_por' => auth()->id(),
                            'estado' => $record->estado === 'pendiente' ? 'en_revision' : $record->estado,
                            'observaciones' => $data['observaciones'] ?? $record->observaciones,
                            'respondido_at' => now(),
                            'respondido_por' => auth()->id(),
                        ]);
                    }),

                Tables\Actions\Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (SolicitudBeneficio $r) => ! in_array($r->estado, ['aprobada', 'entregada', 'rechazada']))
                    ->form([
                        Forms\Components\Textarea::make('observaciones')
                            ->label('Observaciones (opcional)')
                            ->rows(2),
                    ])
                    ->action(function (SolicitudBeneficio $record, array $data): void {
                        $record->update([
                            'estado'          => 'aprobada',
                            'observaciones'   => $data['observaciones'] ?? $record->observaciones,
                            'respondido_at'   => now(),
                            'respondido_por'  => auth()->id(),
                            'aprobado_at'     => now(),
                        ]);
                        self::notificarAfiliado($record, 'Solicitud de beneficio aprobada', 'Tu solicitud de "'.$record->beneficio?->titulo.'" fue aprobada. El equipo de ATSA se pondrá en contacto.', 'success');
                    }),

                Tables\Actions\Action::make('en_revision')
                    ->label('En revisión')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn (SolicitudBeneficio $r) => $r->estado === 'pendiente')
                    ->action(fn (SolicitudBeneficio $r) => $r->update(['estado' => 'en_revision', 'respondido_at' => now(), 'respondido_por' => auth()->id()])),

                Tables\Actions\Action::make('observar')
                    ->label('Falta documentación')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('warning')
                    ->visible(fn (SolicitudBeneficio $r) => ! in_array($r->estado, ['aprobada', 'rechazada', 'entregada']))
                    ->form([
                        Forms\Components\Textarea::make('observacion_afiliado')
                            ->label('Mensaje para el afiliado')
                            ->required()
                            ->rows(3),
                        Forms\Components\Textarea::make('observaciones')
                            ->label('Observación interna')
                            ->rows(2),
                    ])
                    ->action(function (SolicitudBeneficio $record, array $data): void {
                        $record->update([
                            'estado' => 'observada',
                            'observacion_afiliado' => $data['observacion_afiliado'],
                            'observaciones' => $data['observaciones'] ?? $record->observaciones,
                            'respondido_at' => now(),
                            'respondido_por' => auth()->id(),
                        ]);

                        self::notificarAfiliado($record, 'Solicitud observada', $data['observacion_afiliado'], 'warning');
                    }),

                Tables\Actions\Action::make('entregar')
                    ->label('Entregado')
                    ->icon('heroicon-o-check-badge')
                    ->color('gray')
                    ->visible(fn (SolicitudBeneficio $r) => $r->estado === 'aprobada')
                    ->requiresConfirmation()
                    ->action(fn (SolicitudBeneficio $r) => $r->update(['estado' => 'entregada', 'entregado_at' => now()])),

                Tables\Actions\Action::make('rechazar')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (SolicitudBeneficio $r) => ! in_array($r->estado, ['rechazada', 'entregada']))
                    ->form([
                        Forms\Components\Textarea::make('observaciones')
                            ->label('Motivo del rechazo')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (SolicitudBeneficio $record, array $data): void {
                        $record->update([
                            'estado'         => 'rechazada',
                            'observaciones'  => $data['observaciones'],
                            'respondido_at'  => now(),
                            'respondido_por' => auth()->id(),
                        ]);
                        self::notificarAfiliado($record, 'Solicitud de beneficio rechazada', 'Tu solicitud de "'.$record->beneficio?->titulo.'" no pudo ser procesada. Motivo: '.$data['observaciones'], 'danger');
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
            'index'  => Pages\ListSolicitudBeneficios::route('/'),
            'create' => Pages\CreateSolicitudBeneficio::route('/create'),
            'edit'   => Pages\EditSolicitudBeneficio::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['afiliado', 'beneficio', 'secretaria', 'asignadoA']);
        $user = auth()->user();

        if ($user?->shouldScopeAdminWorkflowToSecretaria()) {
            $query->where('secretaria_id', $user->secretaria_id);
        }

        return $query;
    }

    private static function notificarAfiliado(SolicitudBeneficio $solicitud, string $titulo, string $cuerpo, string $color): void
    {
        if (! $solicitud->afiliado_id) {
            return;
        }

        $afiliado = User::find($solicitud->afiliado_id);

        if (! $afiliado) {
            return;
        }

        Notification::make()
            ->title($titulo)
            ->body($cuerpo)
            ->color($color)
            ->sendToDatabase($afiliado);
    }

    private static function historialHtml(?SolicitudBeneficio $solicitud): HtmlString
    {
        if (! $solicitud?->exists) {
            return new HtmlString('<p class="text-sm text-gray-500">Sin movimientos todavía.</p>');
        }

        $items = $solicitud->movimientos()
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
}
