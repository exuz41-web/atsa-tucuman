<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\SolicitudAfiliacionResource\Pages;
use App\Models\SolicitudAfiliacion;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SolicitudAfiliacionResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = SolicitudAfiliacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationGroup = 'Afiliación y padrón';

    protected static ?string $navigationLabel = 'Solicitudes de afiliación';

    protected static ?string $modelLabel = 'solicitud de afiliación';

    protected static ?string $pluralModelLabel = 'solicitudes de afiliación';

    protected static ?string $slug = 'solicitudes-afiliacion';

    protected static ?int $navigationSort = 20;

    protected static ?string $panelScope = 'admin';

    public static function getNavigationBadge(): ?string
    {
        return (string) SolicitudAfiliacion::where('estado', 'pendiente')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos personales')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('apellido_nombre')
                        ->label('Apellido y nombre')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2),

                    Forms\Components\Select::make('estado')
                        ->label('Estado')
                        ->options(self::estados())
                        ->required()
                        ->native(false),

                    Forms\Components\DatePicker::make('fecha_nacimiento')
                        ->label('Fecha de nacimiento')
                        ->displayFormat('d/m/Y'),

                    Forms\Components\TextInput::make('nacionalidad')
                        ->label('Nacionalidad')
                        ->maxLength(80),

                    Forms\Components\TextInput::make('estado_civil')
                        ->label('Estado civil')
                        ->maxLength(80),

                    Forms\Components\Select::make('tipo_documento')
                        ->label('Tipo documento')
                        ->options(['DNI' => 'DNI', 'LC' => 'LC', 'LE' => 'LE', 'Pasaporte' => 'Pasaporte'])
                        ->native(false),

                    Forms\Components\TextInput::make('numero_documento')
                        ->label('N° documento')
                        ->maxLength(30),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('telefono')
                        ->label('Teléfono')
                        ->maxLength(80),

                    Forms\Components\TextInput::make('domicilio')
                        ->label('Domicilio')
                        ->maxLength(255)
                        ->columnSpan(2),
                ]),

            Forms\Components\Section::make('Datos laborales')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('establecimiento')
                        ->label('Establecimiento')
                        ->maxLength(255)
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('condicion_institucion')
                        ->label('Condición')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('nivel')
                        ->label('Nivel')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('legajo')
                        ->label('Legajo')
                        ->maxLength(50),

                    Forms\Components\TextInput::make('profesion')
                        ->label('Profesión')
                        ->maxLength(150),

                    Forms\Components\TextInput::make('filial_preferida')
                        ->label('Filial preferida')
                        ->maxLength(150),
                ]),

            Forms\Components\Section::make('Afiliador')
                ->columns(2)
                ->collapsed()
                ->schema([
                    Forms\Components\TextInput::make('nombre_afiliador')
                        ->label('Nombre del afiliador')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('celular_afiliador')
                        ->label('Celular del afiliador')
                        ->maxLength(80),
                ]),

            Forms\Components\Section::make('Documentación adjunta')
                ->columns(4)
                ->schema([
                    Forms\Components\FileUpload::make('dni_frente')
                        ->label('DNI frente')
                        ->disk('public')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                        ->downloadable()
                        ->openable(),

                    Forms\Components\FileUpload::make('dni_dorso')
                        ->label('DNI dorso')
                        ->disk('public')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                        ->downloadable()
                        ->openable(),

                    Forms\Components\FileUpload::make('recibo_sueldo')
                        ->label('Recibo de sueldo')
                        ->disk('public')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                        ->downloadable()
                        ->openable(),

                    Forms\Components\FileUpload::make('formulario_firmado')
                        ->label('Formulario firmado')
                        ->disk('public')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                        ->downloadable()
                        ->openable(),
                ]),

            Forms\Components\Section::make('Gestión administrativa')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('numero_afiliado_generado')
                        ->label('N° afiliado generado')
                        ->disabled()
                        ->placeholder('Se genera al aprobar'),

                    Forms\Components\DateTimePicker::make('reviewed_at')
                        ->label('Fecha de revisión')
                        ->disabled(),

                    Forms\Components\Textarea::make('observaciones_admin')
                        ->label('Observaciones internas')
                        ->helperText('Notas del equipo, no visibles para el aspirante.')
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
                Tables\Columns\TextColumn::make('apellido_nombre')
                    ->label('Apellido y nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('numero_documento')
                    ->label('DNI')
                    ->searchable(),

                Tables\Columns\TextColumn::make('filial_preferida')
                    ->label('Filial')
                    ->placeholder('—')
                    ->limit(25),

                Tables\Columns\TextColumn::make('numero_afiliado_generado')
                    ->label('N° afiliado')
                    ->placeholder('Pendiente')
                    ->badge()
                    ->color('success'),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => self::estados()[$state] ?? ucfirst($state))
                    ->colors([
                        'warning' => 'pendiente',
                        'info'    => 'en_revision',
                        'primary' => 'observada',
                        'success' => 'aprobada',
                        'danger'  => 'rechazada',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Enviada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options(self::estados()),
            ])
            ->actions([
                Tables\Actions\Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (SolicitudAfiliacion $r) => ! in_array($r->estado, ['aprobada', 'rechazada']))
                    ->form([
                        Forms\Components\TextInput::make('numero_afiliado')
                            ->label('N° de afiliado a asignar')
                            ->helperText('Dejá vacío para generar automáticamente.')
                            ->maxLength(30),
                        Forms\Components\Textarea::make('observaciones_admin')
                            ->label('Observaciones (opcional)')
                            ->rows(2),
                    ])
                    ->action(function (SolicitudAfiliacion $record, array $data): void {
                        $numero = filled($data['numero_afiliado'])
                            ? $data['numero_afiliado']
                            : 'AFF-'.now()->format('Y').'-'.strtoupper(Str::random(5));

                        $record->update([
                            'estado'                    => 'aprobada',
                            'numero_afiliado_generado'  => $numero,
                            'reviewed_by'               => auth()->id(),
                            'reviewed_at'               => now(),
                            'observaciones_admin'       => $data['observaciones_admin'] ?? $record->observaciones_admin,
                        ]);

                        Notification::make()
                            ->title('✅ Solicitud aprobada correctamente')
                            ->body("Número asignado: {$numero}")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('en_revision')
                    ->label('En revisión')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn (SolicitudAfiliacion $r) => $r->estado === 'pendiente')
                    ->action(fn (SolicitudAfiliacion $r) => $r->update([
                        'estado'      => 'en_revision',
                        'reviewed_by' => auth()->id(),
                        'reviewed_at' => now(),
                    ])),

                Tables\Actions\Action::make('observar')
                    ->label('Observar')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('warning')
                    ->visible(fn (SolicitudAfiliacion $r) => in_array($r->estado, ['pendiente', 'en_revision']))
                    ->form([
                        Forms\Components\Textarea::make('observaciones_admin')
                            ->label('Observación a registrar')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(fn (SolicitudAfiliacion $record, array $data) => $record->update([
                        'estado'              => 'observada',
                        'observaciones_admin' => $data['observaciones_admin'],
                        'reviewed_by'         => auth()->id(),
                        'reviewed_at'         => now(),
                    ])),

                Tables\Actions\Action::make('rechazar')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (SolicitudAfiliacion $r) => ! in_array($r->estado, ['aprobada', 'rechazada']))
                    ->form([
                        Forms\Components\Textarea::make('observaciones_admin')
                            ->label('Motivo del rechazo')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(fn (SolicitudAfiliacion $record, array $data) => $record->update([
                        'estado'              => 'rechazada',
                        'observaciones_admin' => $data['observaciones_admin'],
                        'reviewed_by'         => auth()->id(),
                        'reviewed_at'         => now(),
                    ])),

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
            'index'  => Pages\ListSolicitudAfiliacions::route('/'),
            'create' => Pages\CreateSolicitudAfiliacion::route('/create'),
            'edit'   => Pages\EditSolicitudAfiliacion::route('/{record}/edit'),
        ];
    }

    public static function estados(): array
    {
        return [
            'pendiente'   => 'Pendiente',
            'en_revision' => 'En revisión',
            'observada'   => 'Observada',
            'aprobada'    => 'Aprobada',
            'rechazada'   => 'Rechazada',
        ];
    }
}
