<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\CentPermisoExamenResource\Pages;
use App\Models\CentPermisoExamen;
use App\Models\CentCuota;
use App\Models\MesaExamenCent;
use App\Models\User;
use App\Services\Cent\CentNotificar;
use App\Services\Cent\FilamentSedeScope;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentPermisoExamenResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = CentPermisoExamen::class;
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationGroup = 'Alumnos y legajos';
    protected static ?string $navigationLabel = 'Permisos de examen';

    protected static ?int $navigationSort = 40;
    protected static ?string $modelLabel = 'permiso de examen';
    protected static ?string $pluralModelLabel = 'permisos de examen';
    protected static ?string $slug = 'permisos-examen';
    protected static ?string $panelScope = 'cent';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('codigo')->disabled()->dehydrated(false),
            Forms\Components\Select::make('alumno_id')
                ->label('Alumno')
                ->options(fn () => User::where('cent_role', 'alumno')->orderBy('name')->pluck('name', 'id'))
                ->searchable()
                ->required(),
            Forms\Components\Select::make('mesa_examen_cent_id')
                ->label('Mesa')
                ->options(fn () => MesaExamenCent::with(['materia', 'sede'])->latest('fecha')->get()->mapWithKeys(
                    fn ($mesa) => [$mesa->id => (($mesa->materia->name ?? 'Materia')).' - '.(($mesa->sede->nombre ?? 'Sede')).' - '.$mesa->fecha?->format('d/m/Y')]
                ))
                ->searchable()
                ->required(),
            Forms\Components\Select::make('cent_cuota_id')
                ->label('Cuota/pago asociado')
                ->options(fn () => CentCuota::with('alumno')->latest()->get()->mapWithKeys(
                    fn ($cuota) => [$cuota->id => (($cuota->alumno->name ?? 'Alumno')).' - '.$cuota->concepto.' - '.$cuota->estado]
                ))
                ->searchable(),
            Forms\Components\TextInput::make('monto')->numeric()->default(0)->required(),
            Forms\Components\Select::make('estado')->options([
                'pendiente_pago' => 'Pendiente de pago',
                'habilitado' => 'Habilitado',
                'usado' => 'Usado',
                'anulado' => 'Anulado',
            ])->default('pendiente_pago')->required()->native(false),
            Forms\Components\DateTimePicker::make('habilitado_at')->label('Habilitado el')->native(false),
            Forms\Components\DateTimePicker::make('usado_at')->label('Usado el')->native(false),
            Forms\Components\Textarea::make('observaciones')->rows(3)->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('alumno.name')->label('Alumno')->searchable(),
                Tables\Columns\TextColumn::make('mesa.materia.name')->label('Materia')->searchable(),
                Tables\Columns\TextColumn::make('mesa.fecha')->label('Mesa')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('monto')->money('ARS')->sortable(),
                Tables\Columns\TextColumn::make('estado')->badge()->color(fn ($state) => match ($state) {
                    'habilitado' => 'success',
                    'usado' => 'info',
                    'anulado' => 'danger',
                    default => 'warning',
                }),
                Tables\Columns\TextColumn::make('cuota.estado')->label('Pago')->badge()->placeholder('-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')->options([
                    'pendiente_pago' => 'Pendiente de pago',
                    'habilitado' => 'Habilitado',
                    'usado' => 'Usado',
                    'anulado' => 'Anulado',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('habilitar')
                    ->label('Habilitar')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => $record->estado !== 'habilitado')
                    ->action(function (CentPermisoExamen $record): void {
                        $record->update([
                            'estado' => 'habilitado',
                            'habilitado_at' => now(),
                            'habilitado_por' => auth()->id(),
                        ]);
                        CentNotificar::usuario($record->alumno_id, 'Permiso de examen habilitado', 'Ya podés descargar el permiso '.$record->codigo.'.', 'permiso', route('cent.alumno.permisos.pdf', $record));
                    }),
                Tables\Actions\Action::make('imprimir')
                    ->label('Imprimir')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('cent.alumno.permisos.pdf', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return FilamentSedeScope::sedeId()
            ? parent::getEloquentQuery()->whereHas('mesa', fn ($q) => $q->where('cent_sede_id', FilamentSedeScope::sedeId()))
            : parent::getEloquentQuery();
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
            'index' => Pages\ListCentPermisoExamens::route('/'),
            'create' => Pages\CreateCentPermisoExamen::route('/create'),
            'edit' => Pages\EditCentPermisoExamen::route('/{record}/edit'),
        ];
    }
}
