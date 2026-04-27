<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\SolicitudBeneficioResource\Pages;
use App\Models\SolicitudBeneficio;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
        return (string) SolicitudBeneficio::where('estado', 'pendiente')->count() ?: null;
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
                        ->relationship('afiliado', 'name', fn (Builder $q) => $q->where('role', 'afiliado')->orWhereNotNull('numero_afiliado'))
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('beneficio_id')
                        ->label('Beneficio')
                        ->relationship('beneficio', 'titulo')
                        ->searchable()
                        ->preload()
                        ->required(),

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
                    ->colors([
                        'warning' => 'pendiente',
                        'info'    => 'en_revision',
                        'success' => 'aprobada',
                        'danger'  => 'rechazada',
                        'gray'    => 'entregada',
                    ]),

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
            ])
            ->actions([
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
                        ]);
                        self::notificarAfiliado($record, 'Solicitud de beneficio aprobada', 'Tu solicitud de "'.$record->beneficio?->titulo.'" fue aprobada. El equipo de ATSA se pondrá en contacto.', 'success');
                    }),

                Tables\Actions\Action::make('en_revision')
                    ->label('En revisión')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn (SolicitudBeneficio $r) => $r->estado === 'pendiente')
                    ->action(fn (SolicitudBeneficio $r) => $r->update(['estado' => 'en_revision', 'respondido_at' => now(), 'respondido_por' => auth()->id()])),

                Tables\Actions\Action::make('entregar')
                    ->label('Entregado')
                    ->icon('heroicon-o-check-badge')
                    ->color('gray')
                    ->visible(fn (SolicitudBeneficio $r) => $r->estado === 'aprobada')
                    ->requiresConfirmation()
                    ->action(fn (SolicitudBeneficio $r) => $r->update(['estado' => 'entregada'])),

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
}
