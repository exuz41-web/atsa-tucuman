<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\CentUserResource\Pages;
use App\Models\User;
use App\Services\Cent\FilamentSedeScope;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CentUserResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'CENT N°74';
    protected static ?string $navigationLabel = 'Usuarios CENT';
    protected static ?string $modelLabel = 'usuario CENT';
    protected static ?string $pluralModelLabel = 'usuarios CENT';
    protected static ?string $slug = 'usuarios';
    protected static ?string $panelScope = 'cent';

    public static function rolesCent(): array
    {
        return [
            'alumno' => 'Alumno',
            'docente' => 'Docente',
            'coordinador' => 'Coordinador',
            'directivo' => 'Directivo',
        ];
    }

    public static function roleColor(?string $role): string
    {
        return match ($role) {
            'directivo' => 'primary',
            'coordinador' => 'warning',
            'docente' => 'info',
            'alumno' => 'success',
            default => 'gray',
        };
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->whereNotNull('cent_role');

        return FilamentSedeScope::sedeId()
            ? $query->where(function ($query) {
                $query->where('cent_sede_id', FilamentSedeScope::sedeId())
                    ->orWhereHas('matriculasCent', fn ($matricula) => $matricula->where('cent_sede_id', FilamentSedeScope::sedeId()))
                    ->orWhereHas('comisionesDocente', fn ($comision) => $comision->where('cent_sede_id', FilamentSedeScope::sedeId()));
            })
            : $query;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos personales')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre completo')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('dni')
                        ->label('DNI')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(30),
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->label('Teléfono')
                        ->tel()
                        ->maxLength(60),
                    Forms\Components\TextInput::make('address')
                        ->label('Dirección')
                        ->maxLength(255)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Forms\Components\Section::make('Acceso académico')
                ->schema([
                    Forms\Components\Select::make('cent_role')
                        ->label('Rol CENT')
                        ->options(self::rolesCent())
                        ->native(false)
                        ->required(),
                    Forms\Components\Toggle::make('active')
                        ->label('Activo')
                        ->default(true),
                    Forms\Components\Select::make('cent_sede_id')
                        ->label('Sede asignada')
                        ->relationship('centSede', 'nombre')
                        ->searchable()
                        ->preload()
                        ->helperText('Para coordinadores o directivos de una sede específica.'),
                    Forms\Components\TextInput::make('password')
                        ->label('Contraseña')
                        ->password()
                        ->revealable()
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->suffixAction(
                            Forms\Components\Actions\Action::make('generar')
                                ->label('Generar')
                                ->icon('heroicon-o-key')
                                ->action(fn (Forms\Set $set) => $set('password', Str::password(10)))
                        )
                        ->maxLength(255),
                ])
                ->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dni')
                    ->label('DNI')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('cent_role')
                    ->label('Rol CENT')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => self::rolesCent()[$state] ?? ucfirst((string) $state))
                    ->color(fn (?string $state): string => self::roleColor($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('centSede.nombre')
                    ->label('Sede')
                    ->placeholder('Sin sede')
                    ->sortable(),
                Tables\Columns\TextColumn::make('matriculas_cent_count')
                    ->counts('matriculasCent')
                    ->label('Matrículas')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('comisiones_docente_count')
                    ->counts('comisionesDocente')
                    ->label('Comisiones')
                    ->badge()
                    ->color('info'),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('Activo'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Alta')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('cent_role')
                    ->label('Rol CENT')
                    ->options(self::rolesCent()),
                Tables\Filters\SelectFilter::make('cent_sede_id')
                    ->label('Sede')
                    ->relationship('centSede', 'nombre'),
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Activo'),
            ])
            ->actions([
                Tables\Actions\Action::make('resetearPassword')
                    ->label('Resetear clave')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (User $record): void {
                        $password = Str::password(10);
                        $record->update(['password' => $password]);

                        Notification::make()
                            ->title('Nueva clave generada')
                            ->body($password)
                            ->success()
                            ->persistent()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \Filament\Facades\Filament::getCurrentPanel()?->getId() === 'cent' && static::canViewAny();
    }

    public static function canAccess(): bool
    {
        return \Filament\Facades\Filament::getCurrentPanel()?->getId() === 'cent' && static::canViewAny();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCentUsers::route('/'),
            'create' => Pages\CreateCentUser::route('/create'),
            'edit' => Pages\EditCentUser::route('/{record}/edit'),
        ];
    }
}
