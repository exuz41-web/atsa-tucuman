<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\UserResource\Pages;
use App\Models\Filial;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Padrón sindical';

    protected static ?string $navigationLabel = 'Afiliados';

    protected static ?string $modelLabel = 'afiliado';

    protected static ?string $pluralModelLabel = 'afiliados';

    protected static ?string $slug = 'users';

    protected static ?int $navigationSort = 1;

    protected static ?string $panelScope = 'admin';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where(function (Builder $query): void {
            $query->where('role', 'afiliado')->orWhereNotNull('numero_afiliado');
        });
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->where('active', true)->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos personales')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre completo')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('numero_afiliado')
                        ->label('N° de afiliado')
                        ->maxLength(50),

                    Forms\Components\TextInput::make('dni')
                        ->label('DNI')
                        ->maxLength(20),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255)
                        ->unique(User::class, 'email', ignoreRecord: true),

                    Forms\Components\TextInput::make('phone')
                        ->label('Teléfono')
                        ->maxLength(80),

                    Forms\Components\TextInput::make('address')
                        ->label('Domicilio')
                        ->maxLength(255)
                        ->columnSpan(2),

                    Forms\Components\Select::make('estado_afiliado')
                        ->label('Estado sindical')
                        ->options([
                            'activo'    => 'Activo',
                            'inactivo'  => 'Inactivo',
                            'suspendido'=> 'Suspendido',
                            'baja'      => 'Baja',
                        ])
                        ->native(false),
                ]),

            Forms\Components\Section::make('Datos laborales')
                ->columns(3)
                ->schema([
                    Forms\Components\Select::make('filial_id')
                        ->label('Filial')
                        ->relationship('filial', 'name')
                        ->searchable()
                        ->preload(),

                    Forms\Components\TextInput::make('lugar_trabajo')
                        ->label('Lugar de trabajo')
                        ->maxLength(255)
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('categoria_laboral')
                        ->label('Categoría laboral')
                        ->maxLength(120),

                    Forms\Components\TextInput::make('legajo_laboral')
                        ->label('Legajo')
                        ->maxLength(80),

                    Forms\Components\TextInput::make('obra_social')
                        ->label('Obra social')
                        ->maxLength(150),

                    Forms\Components\Select::make('tipo_afiliado')
                        ->label('Tipo de afiliado')
                        ->options([
                            'docente'       => 'Docente',
                            'no_docente'    => 'No docente',
                            'jubilado'      => 'Jubilado',
                            'otro'          => 'Otro',
                        ])
                        ->native(false),

                    Forms\Components\Toggle::make('es_delegado_gremial')
                        ->label('Delegado gremial'),

                    Forms\Components\Toggle::make('es_congresal')
                        ->label('Congresal'),

                    Forms\Components\DatePicker::make('fecha_alta')
                        ->label('Fecha de alta')
                        ->displayFormat('d/m/Y'),
                ]),

            Forms\Components\Section::make('Carnet')
                ->columns(3)
                ->schema([
                    Forms\Components\Toggle::make('carnet_activo')
                        ->label('Carnet activo'),

                    Forms\Components\DatePicker::make('carnet_vencimiento')
                        ->label('Vence el')
                        ->displayFormat('d/m/Y'),

                    Forms\Components\DateTimePicker::make('carnet_emitido_at')
                        ->label('Emitido el')
                        ->displayFormat('d/m/Y H:i'),

                    Forms\Components\FileUpload::make('foto_perfil')
                        ->label('Foto de perfil')
                        ->image()
                        ->disk('public')
                        ->directory('fotos-perfil')
                        ->imageEditor()
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Acceso al sistema')
                ->columns(2)
                ->collapsed()
                ->schema([
                    Forms\Components\Toggle::make('active')
                        ->label('Cuenta activa')
                        ->default(true),

                    Forms\Components\TextInput::make('password')
                        ->label('Nueva contraseña')
                        ->password()
                        ->revealable()
                        ->minLength(8)
                        ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('foto_perfil')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn (User $r): string => 'https://ui-avatars.com/api/?name='.urlencode($r->name).'&background=1e3a5f&color=ffffff&size=80')
                    ->width(40)
                    ->height(40),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('numero_afiliado')
                    ->label('N° afiliado')
                    ->placeholder('—')
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('dni')
                    ->label('DNI')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('filial.name')
                    ->label('Filial')
                    ->placeholder('Sin filial')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tipo_afiliado')
                    ->label('Tipo')
                    ->formatStateUsing(fn (?string $s): string => match ($s) {
                        'docente'    => 'Docente',
                        'no_docente' => 'No docente',
                        'jubilado'   => 'Jubilado',
                        default      => ucfirst($s ?? '—'),
                    })
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('carnet_activo')
                    ->label('Carnet')
                    ->boolean()
                    ->trueIcon('heroicon-o-identification')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\BadgeColumn::make('estado_afiliado')
                    ->label('Estado')
                    ->formatStateUsing(fn (?string $s): string => match ($s) {
                        'activo'     => 'Activo',
                        'inactivo'   => 'Inactivo',
                        'suspendido' => 'Suspendido',
                        'baja'       => 'Baja',
                        default      => 'Activo',
                    })
                    ->colors([
                        'success' => 'activo',
                        'warning' => 'inactivo',
                        'danger'  => fn (?string $s) => in_array($s, ['suspendido', 'baja']),
                        'gray'    => fn (?string $s) => $s === null,
                    ]),

                Tables\Columns\IconColumn::make('active')
                    ->label('Acceso')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Alta')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('filial_id')
                    ->label('Filial')
                    ->relationship('filial', 'name'),

                Tables\Filters\SelectFilter::make('tipo_afiliado')
                    ->label('Tipo')
                    ->options([
                        'docente'    => 'Docente',
                        'no_docente' => 'No docente',
                        'jubilado'   => 'Jubilado',
                        'otro'       => 'Otro',
                    ]),

                Tables\Filters\SelectFilter::make('estado_afiliado')
                    ->label('Estado sindical')
                    ->options([
                        'activo'     => 'Activo',
                        'inactivo'   => 'Inactivo',
                        'suspendido' => 'Suspendido',
                        'baja'       => 'Baja',
                    ]),

                Tables\Filters\TernaryFilter::make('carnet_activo')
                    ->label('Carnet activo'),

                Tables\Filters\TernaryFilter::make('es_delegado_gremial')
                    ->label('Delegado gremial'),
            ])
            ->actions([
                Tables\Actions\Action::make('activar_carnet')
                    ->label('Emitir carnet')
                    ->icon('heroicon-o-identification')
                    ->color('success')
                    ->visible(fn (User $r) => ! $r->carnet_activo)
                    ->form([
                        Forms\Components\DatePicker::make('carnet_vencimiento')
                            ->label('Fecha de vencimiento')
                            ->required()
                            ->default(now()->addYear()->format('Y-m-d'))
                            ->displayFormat('d/m/Y'),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->update([
                            'carnet_activo'    => true,
                            'carnet_vencimiento' => $data['carnet_vencimiento'],
                            'carnet_emitido_at'  => now(),
                        ]);
                        Notification::make()
                            ->title('Carnet emitido')
                            ->body("El carnet de {$record->name} fue activado.")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('renovar_carnet')
                    ->label('Renovar carnet')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->visible(fn (User $r) => $r->carnet_activo)
                    ->form([
                        Forms\Components\DatePicker::make('carnet_vencimiento')
                            ->label('Nueva fecha de vencimiento')
                            ->required()
                            ->default(now()->addYear()->format('Y-m-d'))
                            ->displayFormat('d/m/Y'),
                    ])
                    ->action(fn (User $record, array $data) => $record->update([
                        'carnet_vencimiento' => $data['carnet_vencimiento'],
                        'carnet_emitido_at'  => now(),
                    ])),

                Tables\Actions\Action::make('reset_password')
                    ->label('Reset password')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->form([
                        Forms\Components\TextInput::make('password')
                            ->label('Nueva contraseña')
                            ->password()
                            ->revealable()
                            ->required()
                            ->minLength(8),
                    ])
                    ->action(fn (User $record, array $data) => $record->update(['password' => Hash::make($data['password'])])),

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
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
