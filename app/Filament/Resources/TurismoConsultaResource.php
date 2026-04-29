<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\TurismoConsultaResource\Pages;
use App\Models\TurismoConsulta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TurismoConsultaResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = TurismoConsulta::class;

    protected static ?string $navigationIcon = 'heroicon-o-sun';

    protected static ?string $navigationGroup = 'Secretarías y beneficios';

    protected static ?string $navigationLabel = 'Consultas de turismo';

    protected static ?string $modelLabel = 'consulta de turismo';

    protected static ?string $pluralModelLabel = 'consultas de turismo';

    protected static ?string $slug = 'turismo-consultas';

    protected static ?int $navigationSort = 70;

    protected static ?string $panelScope = 'admin';

    public static function getNavigationBadge(): ?string
    {
        return (string) TurismoConsulta::where('estado', 'pendiente')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del consultante')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('nombre')
                        ->label('Nombre y apellido')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2),

                    Forms\Components\Select::make('estado')
                        ->label('Estado')
                        ->options(self::estados())
                        ->required()
                        ->native(false),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('telefono')
                        ->label('Teléfono')
                        ->maxLength(80),

                    Forms\Components\TextInput::make('dni')
                        ->label('DNI')
                        ->maxLength(20),

                    Forms\Components\TextInput::make('numero_afiliado')
                        ->label('N° de afiliado')
                        ->maxLength(50),

                    Forms\Components\Select::make('beneficio')
                        ->label('Beneficio consultado')
                        ->options(self::beneficios())
                        ->native(false),

                    Forms\Components\DatePicker::make('fecha_estimada')
                        ->label('Fecha estimada de viaje')
                        ->displayFormat('d/m/Y'),
                ]),

            Forms\Components\Section::make('Mensaje y seguimiento')
                ->schema([
                    Forms\Components\Textarea::make('mensaje')
                        ->label('Mensaje')
                        ->rows(5)
                        ->disabled()
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('observaciones')
                        ->label('Observaciones / Respuesta del equipo')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('beneficio')
                    ->label('Beneficio')
                    ->formatStateUsing(fn (?string $state): string => self::beneficios()[$state] ?? ucfirst((string) $state))
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('fecha_estimada')
                    ->label('Fecha viaje')
                    ->date('d/m/Y')
                    ->placeholder('—')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => self::estados()[$state] ?? ucfirst($state))
                    ->colors([
                        'warning' => 'pendiente',
                        'info'    => 'en_proceso',
                        'success' => 'respondida',
                        'gray'    => 'cerrada',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Recibida')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('beneficio')
                    ->label('Beneficio')
                    ->options(self::beneficios()),
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options(self::estados()),
            ])
            ->actions([
                Tables\Actions\Action::make('responder')
                    ->label('Responder')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->visible(fn (TurismoConsulta $r) => ! in_array($r->estado, ['respondida', 'cerrada']))
                    ->form([
                        Forms\Components\Textarea::make('observaciones')
                            ->label('Respuesta / Observaciones')
                            ->required()
                            ->rows(5)
                            ->default(fn (TurismoConsulta $r) => $r->observaciones),
                    ])
                    ->action(fn (TurismoConsulta $record, array $data) => $record->update([
                        'observaciones' => $data['observaciones'],
                        'estado'        => 'respondida',
                    ])),

                Tables\Actions\Action::make('en_proceso')
                    ->label('En proceso')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->visible(fn (TurismoConsulta $r) => $r->estado === 'pendiente')
                    ->action(fn (TurismoConsulta $r) => $r->update(['estado' => 'en_proceso'])),

                Tables\Actions\Action::make('cerrar')
                    ->label('Cerrar')
                    ->icon('heroicon-o-archive-box')
                    ->color('gray')
                    ->visible(fn (TurismoConsulta $r) => in_array($r->estado, ['respondida', 'en_proceso']))
                    ->requiresConfirmation()
                    ->action(fn (TurismoConsulta $r) => $r->update(['estado' => 'cerrada'])),

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
            'index'  => Pages\ListTurismoConsultas::route('/'),
            'create' => Pages\CreateTurismoConsulta::route('/create'),
            'edit'   => Pages\EditTurismoConsulta::route('/{record}/edit'),
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

    public static function beneficios(): array
    {
        return [
            'hotel'         => 'Hotel convenio',
            'colonia'       => 'Colonia de vacaciones',
            'excursion'     => 'Excursión',
            'traslado'      => 'Traslado',
            'paquete'       => 'Paquete turístico',
            'otro'          => 'Otro',
        ];
    }
}
