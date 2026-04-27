<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\CentClaseResource\Pages;
use App\Models\CentClase;
use App\Models\Comision;
use App\Services\Cent\FilamentSedeScope;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentClaseResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = CentClase::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Aula virtual';
    protected static ?string $navigationLabel = 'Clases';
    protected static ?string $modelLabel = 'clase';
    protected static ?string $pluralModelLabel = 'clases';
    protected static ?string $slug = 'clases';
    protected static ?string $panelScope = 'cent';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('comision_id')
                ->label('Comisión')
                ->options(fn () => Comision::with(['materia', 'sede'])->latest()->get()->mapWithKeys(
                    fn ($comision) => [$comision->id => (($comision->materia->name ?? 'Materia')).' - '.(($comision->sede->nombre ?? 'Sede')).' - '.$comision->year_cycle]
                ))
                ->searchable()
                ->required(),
            Forms\Components\TextInput::make('titulo')->required()->maxLength(255),
            Forms\Components\Select::make('modalidad')->options([
                'presencial' => 'Presencial',
                'virtual' => 'Virtual',
                'mixta' => 'Mixta',
            ])->default('presencial')->required()->native(false),
            Forms\Components\DateTimePicker::make('fecha_inicio')->label('Inicio')->required()->native(false),
            Forms\Components\DateTimePicker::make('fecha_fin')->label('Fin')->native(false),
            Forms\Components\TextInput::make('aula')->maxLength(120),
            Forms\Components\TextInput::make('link_virtual')->label('Link virtual')->url()->maxLength(255),
            Forms\Components\Toggle::make('publicada')->default(true),
            Forms\Components\Textarea::make('descripcion')->rows(4)->columnSpanFull(),
            Forms\Components\Hidden::make('creado_por')->default(fn () => auth()->id()),
        ])->columns(2);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return FilamentSedeScope::sedeId()
            ? parent::getEloquentQuery()->whereHas('comision', fn ($query) => $query->where('cent_sede_id', FilamentSedeScope::sedeId()))
            : parent::getEloquentQuery();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('comision.materia.name')->label('Materia')->searchable(),
                Tables\Columns\TextColumn::make('comision.sede.nombre')->label('Sede')->searchable(),
                Tables\Columns\TextColumn::make('fecha_inicio')->label('Inicio')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('modalidad')->badge()->color(fn ($state) => match ($state) {
                    'virtual' => 'info',
                    'mixta' => 'warning',
                    default => 'success',
                }),
                Tables\Columns\ToggleColumn::make('publicada'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('modalidad')->options([
                    'presencial' => 'Presencial',
                    'virtual' => 'Virtual',
                    'mixta' => 'Mixta',
                ]),
                Tables\Filters\TernaryFilter::make('publicada'),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'cent' && static::canViewAny();
    }

    public static function canAccess(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'cent' && static::canViewAny();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCentClases::route('/'),
            'create' => Pages\CreateCentClase::route('/create'),
            'edit' => Pages\EditCentClase::route('/{record}/edit'),
        ];
    }
}
