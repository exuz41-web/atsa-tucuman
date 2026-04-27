<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\PedidoResource\Pages;
use App\Models\Pedido;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PedidoResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = Pedido::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Atención al afiliado';

    protected static ?string $navigationLabel = 'Pedidos';

    protected static ?string $modelLabel = 'pedido';

    protected static ?string $pluralModelLabel = 'pedidos';

    protected static ?string $slug = 'pedidos';

    protected static ?int $navigationSort = 1;

    protected static ?string $panelScope = 'admin';

    public static function getNavigationBadge(): ?string
    {
        return (string) Pedido::whereIn('estado', ['pendiente', 'en_revision'])->count() ?: null;
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
                        ->relationship('afiliado', 'name', fn (Builder $q) => $q->where('role', 'afiliado')->orWhereNotNull('numero_afiliado'))
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('tipo')
                        ->label('Tipo')
                        ->options(self::tipos())
                        ->required()
                        ->native(false),

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
                    ->colors([
                        'warning' => 'pendiente',
                        'info'    => 'en_revision',
                        'success' => 'aprobado',
                        'danger'  => 'rechazado',
                        'gray'    => 'entregado',
                    ]),

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
            ])
            ->actions([
                Tables\Actions\Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Pedido $r) => ! in_array($r->estado, ['aprobado', 'entregado']))
                    ->requiresConfirmation()
                    ->modalHeading('Aprobar pedido')
                    ->modalDescription('El pedido pasará a estado Aprobado.')
                    ->action(function (Pedido $record): void {
                        $record->update(['estado' => 'aprobado']);
                        self::notificarAfiliado($record, 'Pedido aprobado', 'Tu solicitud de '.self::tipos()[$record->tipo].' fue aprobada. Pronto nos contactaremos para coordinar la entrega.', 'success');
                    }),

                Tables\Actions\Action::make('en_revision')
                    ->label('En revisión')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn (Pedido $r) => $r->estado === 'pendiente')
                    ->action(fn (Pedido $r) => $r->update(['estado' => 'en_revision'])),

                Tables\Actions\Action::make('entregar')
                    ->label('Entregado')
                    ->icon('heroicon-o-check-badge')
                    ->color('gray')
                    ->visible(fn (Pedido $r) => $r->estado === 'aprobado')
                    ->requiresConfirmation()
                    ->modalHeading('Marcar como entregado')
                    ->action(function (Pedido $record): void {
                        $record->update(['estado' => 'entregado']);
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
        return [
            'pendiente'   => 'Pendiente',
            'en_revision' => 'En revisión',
            'aprobado'    => 'Aprobado',
            'rechazado'   => 'Rechazado',
            'entregado'   => 'Entregado',
        ];
    }

    public static function estadoColor(?string $state): string
    {
        return match ($state) {
            'pendiente'   => 'warning',
            'en_revision' => 'info',
            'aprobado'    => 'success',
            'rechazado'   => 'danger',
            'entregado'   => 'gray',
            default       => 'gray',
        };
    }
}
