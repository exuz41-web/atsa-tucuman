<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\ConsultaResource\Pages;
use App\Models\Consulta;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ConsultaResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = Consulta::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Atención al afiliado';

    protected static ?string $navigationLabel = 'Consultas';

    protected static ?string $modelLabel = 'consulta';

    protected static ?string $pluralModelLabel = 'consultas';

    protected static ?string $slug = 'consultas';

    protected static ?int $navigationSort = 2;

    protected static ?string $panelScope = 'admin';

    public static function getNavigationBadge(): ?string
    {
        return (string) Consulta::where('estado', 'pendiente')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos de la consulta')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('afiliado_id')
                        ->label('Afiliado')
                        ->relationship('afiliado', 'name', fn (Builder $q) => $q->where('role', 'afiliado')->orWhereNotNull('numero_afiliado'))
                        ->searchable()
                        ->preload(),

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

                    Forms\Components\DatePicker::make('fecha_solicitada')
                        ->label('Fecha solicitada')
                        ->displayFormat('d/m/Y'),

                    Forms\Components\TextInput::make('asunto')
                        ->label('Asunto')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('mensaje')
                        ->label('Mensaje del afiliado')
                        ->rows(5)
                        ->disabled()
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('respuesta')
                        ->label('Respuesta del equipo')
                        ->helperText('Esta respuesta será visible para el afiliado en su portal.')
                        ->rows(5)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('afiliado.name')
                    ->label('Afiliado')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tipo')
                    ->label('Tipo')
                    ->formatStateUsing(fn (string $state): string => self::tipos()[$state] ?? ucfirst($state))
                    ->colors(['primary' => fn () => true]),

                Tables\Columns\TextColumn::make('asunto')
                    ->label('Asunto')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => self::estados()[$state] ?? ucfirst($state))
                    ->colors([
                        'warning' => 'pendiente',
                        'info'    => 'en_proceso',
                        'success' => 'respondida',
                        'gray'    => 'cerrada',
                    ]),

                Tables\Columns\IconColumn::make('respuesta')
                    ->label('Respondida')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
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
                Tables\Actions\Action::make('responder')
                    ->label('Responder')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->visible(fn (Consulta $r) => ! in_array($r->estado, ['respondida', 'cerrada']))
                    ->form([
                        Forms\Components\Textarea::make('respuesta')
                            ->label('Respuesta')
                            ->required()
                            ->rows(5)
                            ->default(fn (Consulta $r) => $r->respuesta),
                    ])
                    ->action(function (Consulta $record, array $data): void {
                        $record->update([
                            'respuesta' => $data['respuesta'],
                            'estado'    => 'respondida',
                        ]);
                        self::notificarAfiliado(
                            $record,
                            'Tu consulta fue respondida',
                            'El equipo de ATSA respondió tu consulta: "'.$record->asunto.'". Ingresá a tu portal para ver la respuesta.',
                            'success'
                        );
                    }),

                Tables\Actions\Action::make('en_proceso')
                    ->label('En proceso')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->visible(fn (Consulta $r) => $r->estado === 'pendiente')
                    ->action(fn (Consulta $r) => $r->update(['estado' => 'en_proceso'])),

                Tables\Actions\Action::make('cerrar')
                    ->label('Cerrar')
                    ->icon('heroicon-o-archive-box')
                    ->color('gray')
                    ->visible(fn (Consulta $r) => in_array($r->estado, ['respondida', 'en_proceso']))
                    ->requiresConfirmation()
                    ->action(fn (Consulta $r) => $r->update(['estado' => 'cerrada'])),

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
            'index'  => Pages\ListConsultas::route('/'),
            'create' => Pages\CreateConsulta::route('/create'),
            'edit'   => Pages\EditConsulta::route('/{record}/edit'),
        ];
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    private static function notificarAfiliado(Consulta $consulta, string $titulo, string $cuerpo, string $color): void
    {
        if (! $consulta->afiliado_id) {
            return;
        }

        $afiliado = User::find($consulta->afiliado_id);

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
            'consulta'      => 'Consulta',
            'turno'         => 'Turno',
            'asesoramiento' => 'Asesoramiento',
            'beneficio'     => 'Beneficio',
            'gremial'       => 'Gremial',
            'otro'          => 'Otro',
        ];
    }

    public static function estados(): array
    {
        return [
            'pendiente'  => 'Pendiente',
            'en_proceso' => 'En proceso',
            'respondida' => 'Respondida',
            'cerrada'    => 'Cerrada',
        ];
    }
}
