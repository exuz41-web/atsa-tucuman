<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\CentEntregaTrabajoResource\Pages;
use App\Models\CentEntregaTrabajo;
use App\Models\CentTrabajoPractico;
use App\Models\User;
use App\Services\Cent\FilamentSedeScope;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentEntregaTrabajoResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = CentEntregaTrabajo::class;
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationGroup = 'Aula virtual';
    protected static ?string $navigationLabel = 'Entregas';
    protected static ?string $modelLabel = 'entrega';
    protected static ?string $pluralModelLabel = 'entregas';
    protected static ?string $slug = 'entregas';
    protected static ?string $panelScope = 'cent';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('trabajo_practico_id')
                ->label('Trabajo práctico')
                ->options(fn () => CentTrabajoPractico::latest()->pluck('titulo', 'id'))
                ->searchable()
                ->required(),
            Forms\Components\Select::make('alumno_id')
                ->label('Alumno')
                ->options(fn () => User::where('cent_role', 'alumno')->orderBy('name')->pluck('name', 'id'))
                ->searchable()
                ->required(),
            Forms\Components\FileUpload::make('archivo')->disk('local')->directory('cent/entregas')->helperText('Archivo privado. La descarga se realiza desde acciones.'),
            Forms\Components\Select::make('estado')->options([
                'entregado' => 'Entregado',
                'observado' => 'Observado',
                'aprobado' => 'Aprobado',
                'desaprobado' => 'Desaprobado',
            ])->default('entregado')->required()->native(false),
            Forms\Components\TextInput::make('calificacion')->numeric()->minValue(0)->maxValue(10),
            Forms\Components\DateTimePicker::make('entregado_at')->label('Entregado el')->native(false),
            Forms\Components\DateTimePicker::make('corregido_at')->label('Corregido el')->native(false),
            Forms\Components\Textarea::make('comentario')->rows(3),
            Forms\Components\Textarea::make('devolucion')->rows(3),
            Forms\Components\Hidden::make('corregido_por')->default(fn () => auth()->id()),
        ])->columns(2);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return FilamentSedeScope::sedeId()
            ? parent::getEloquentQuery()->whereHas('trabajo.comision', fn ($query) => $query->where('cent_sede_id', FilamentSedeScope::sedeId()))
            : parent::getEloquentQuery();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('trabajo.titulo')->label('Trabajo')->searchable()->limit(35),
                Tables\Columns\TextColumn::make('alumno.name')->label('Alumno')->searchable(),
                Tables\Columns\TextColumn::make('estado')->badge()->color(fn ($state) => match ($state) {
                    'aprobado' => 'success',
                    'desaprobado' => 'danger',
                    'observado' => 'warning',
                    default => 'info',
                }),
                Tables\Columns\TextColumn::make('calificacion')->label('Nota')->sortable(),
                Tables\Columns\TextColumn::make('entregado_at')->label('Entregado')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')->options([
                    'entregado' => 'Entregado',
                    'observado' => 'Observado',
                    'aprobado' => 'Aprobado',
                    'desaprobado' => 'Desaprobado',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('archivo')
                    ->label('Entrega')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn (CentEntregaTrabajo $record) => filled($record->archivo))
                    ->url(fn (CentEntregaTrabajo $record) => route('cent.archivos.entregas', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn ($record) => $record->update(['estado' => 'aprobado', 'corregido_at' => now(), 'corregido_por' => auth()->id()])),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
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
            'index' => Pages\ListCentEntregaTrabajos::route('/'),
            'create' => Pages\CreateCentEntregaTrabajo::route('/create'),
            'edit' => Pages\EditCentEntregaTrabajo::route('/{record}/edit'),
        ];
    }
}
