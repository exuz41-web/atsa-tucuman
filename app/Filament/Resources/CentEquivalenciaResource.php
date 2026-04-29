<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\CentEquivalenciaResource\Pages;
use App\Models\CentEquivalencia;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentEquivalenciaResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = CentEquivalencia::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'Alumnos y legajos';
    protected static ?string $navigationLabel = 'Equivalencias';

    protected static ?int $navigationSort = 50;
    protected static ?string $modelLabel = 'equivalencia';
    protected static ?string $pluralModelLabel = 'equivalencias';
    protected static ?string $slug = 'equivalencias';
    protected static ?string $panelScope = 'cent';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('alumno_id')
                ->label('Alumno')
                ->options(fn () => User::where('cent_role', 'alumno')->orderBy('name')->pluck('name', 'id'))
                ->searchable()
                ->required(),
            Forms\Components\Select::make('materia_id')->label('Materia')->relationship('materia', 'name')->searchable()->preload()->required(),
            Forms\Components\TextInput::make('institucion_origen')->label('Institución de origen'),
            Forms\Components\TextInput::make('nota')->numeric()->minValue(0)->maxValue(10),
            Forms\Components\Select::make('estado')->options(self::estados())->default('solicitada')->required()->native(false),
            Forms\Components\Textarea::make('observaciones')->rows(4)->columnSpanFull(),
            Forms\Components\Hidden::make('aprobado_por')->default(fn () => auth()->id()),
            Forms\Components\DateTimePicker::make('aprobado_at')->native(false),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('alumno.name')->label('Alumno')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('materia.name')->label('Materia')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('materia.carrera.name')->label('Carrera')->toggleable(),
                Tables\Columns\TextColumn::make('nota')->badge()->placeholder('-'),
                Tables\Columns\TextColumn::make('estado')->badge()->formatStateUsing(fn ($state) => self::estados()[$state] ?? $state)->color(fn ($state) => match ($state) {
                    'aprobada' => 'success',
                    'rechazada' => 'danger',
                    default => 'warning',
                }),
                Tables\Columns\TextColumn::make('aprobado_at')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([Tables\Filters\SelectFilter::make('estado')->options(self::estados())])
            ->actions([
                Tables\Actions\Action::make('aprobar')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => $record->estado !== 'aprobada')
                    ->action(fn ($record) => $record->update(['estado' => 'aprobada', 'aprobado_por' => auth()->id(), 'aprobado_at' => now()])),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function estados(): array
    {
        return [
            'solicitada' => 'Solicitada',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
        ];
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
            'index' => Pages\ListCentEquivalencias::route('/'),
            'create' => Pages\CreateCentEquivalencia::route('/create'),
            'edit' => Pages\EditCentEquivalencia::route('/{record}/edit'),
        ];
    }
}
