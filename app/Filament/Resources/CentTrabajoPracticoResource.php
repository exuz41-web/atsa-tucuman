<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CentTrabajoPracticoResource\Pages;
use App\Models\CentTrabajoPractico;
use App\Models\Comision;
use App\Services\Cent\FilamentSedeScope;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentTrabajoPracticoResource extends Resource
{
    protected static ?string $model = CentTrabajoPractico::class;
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationGroup = 'Aula virtual';
    protected static ?string $navigationLabel = 'Trabajos prácticos';
    protected static ?string $modelLabel = 'trabajo práctico';
    protected static ?string $pluralModelLabel = 'trabajos prácticos';
    protected static ?string $slug = 'trabajos-practicos';

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
            Forms\Components\DateTimePicker::make('fecha_publicacion')->label('Publicación')->native(false),
            Forms\Components\DateTimePicker::make('fecha_entrega')->label('Entrega')->native(false),
            Forms\Components\TextInput::make('puntaje_maximo')->label('Puntaje máximo')->numeric(),
            Forms\Components\FileUpload::make('archivo_consigna')->label('Archivo de consigna')->disk('local')->directory('cent/trabajos')->helperText('Archivo privado. La descarga se realiza desde acciones o el aula.'),
            Forms\Components\Toggle::make('acepta_entregas')->label('Acepta entregas')->default(true),
            Forms\Components\Toggle::make('publicado')->default(true),
            Forms\Components\Textarea::make('consigna')->required()->rows(6)->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('fecha_entrega')->label('Entrega')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('entregas_count')->counts('entregas')->label('Entregas')->sortable(),
                Tables\Columns\ToggleColumn::make('publicado'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('publicado'),
                Tables\Filters\TernaryFilter::make('acepta_entregas')->label('Acepta entregas'),
            ])
            ->actions([
                Tables\Actions\Action::make('consigna')
                    ->label('Consigna')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn (CentTrabajoPractico $record) => filled($record->archivo_consigna))
                    ->url(fn (CentTrabajoPractico $record) => route('cent.archivos.trabajos.consigna', $record))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListCentTrabajoPracticos::route('/'),
            'create' => Pages\CreateCentTrabajoPractico::route('/create'),
            'edit' => Pages\EditCentTrabajoPractico::route('/{record}/edit'),
        ];
    }
}
