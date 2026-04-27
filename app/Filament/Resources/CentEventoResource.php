<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CentEventoResource\Pages;
use App\Models\CentEvento;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentEventoResource extends Resource
{
    protected static ?string $model = CentEvento::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Contenido diario – CENT';
    protected static ?int    $navigationSort  = 2;
    protected static ?string $navigationLabel = 'Calendario / Mesas';
    protected static ?string $modelLabel = 'evento';
    protected static ?string $pluralModelLabel = 'calendario académico';
    protected static ?string $slug = 'calendario';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('titulo')->label('Título')->required()->maxLength(255),
            Forms\Components\Select::make('tipo')->options(self::tipos())->default('evento')->required()->native(false),
            Forms\Components\DateTimePicker::make('fecha_inicio')->label('Inicio')->required()->native(false),
            Forms\Components\DateTimePicker::make('fecha_fin')->label('Fin')->native(false),
            Forms\Components\Select::make('cent_sede_id')->label('Sede')->relationship('sede', 'nombre')->searchable()->preload(),
            Forms\Components\Select::make('carrera_id')->label('Carrera')->relationship('carrera', 'name')->searchable()->preload(),
            Forms\Components\Select::make('rol_destino')->label('Destino')->options(self::roles())->default('todos')->required()->native(false),
            Forms\Components\Toggle::make('activo')->default(true),
            Forms\Components\Textarea::make('descripcion')->label('Descripción')->rows(4)->columnSpanFull(),
            Forms\Components\Hidden::make('creado_por')->default(fn () => auth()->id()),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')->label('Título')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('tipo')->badge()->formatStateUsing(fn ($state) => self::tipos()[$state] ?? $state),
                Tables\Columns\TextColumn::make('fecha_inicio')->label('Inicio')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('sede.nombre')->label('Sede')->placeholder('Todas'),
                Tables\Columns\TextColumn::make('carrera.name')->label('Carrera')->placeholder('Todas'),
                Tables\Columns\TextColumn::make('rol_destino')->label('Destino')->badge()->formatStateUsing(fn ($state) => self::roles()[$state] ?? $state),
                Tables\Columns\IconColumn::make('activo')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')->options(self::tipos()),
                Tables\Filters\SelectFilter::make('rol_destino')->options(self::roles()),
                Tables\Filters\SelectFilter::make('sede')->relationship('sede', 'nombre'),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function tipos(): array
    {
        return [
            'clase' => 'Clase',
            'mesa' => 'Mesa',
            'inscripcion' => 'Inscripción',
            'feriado' => 'Feriado',
            'parcial' => 'Parcial',
            'evento' => 'Evento',
            'otro' => 'Otro',
        ];
    }

    public static function roles(): array
    {
        return [
            'todos' => 'Todos',
            'alumno' => 'Alumnos',
            'docente' => 'Docentes',
            'coordinador' => 'Coordinadores',
            'directivo' => 'Directivos',
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'cent';
    }

    public static function canAccess(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'cent';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCentEventos::route('/'),
            'create' => Pages\CreateCentEvento::route('/create'),
            'edit' => Pages\EditCentEvento::route('/{record}/edit'),
        ];
    }
}

