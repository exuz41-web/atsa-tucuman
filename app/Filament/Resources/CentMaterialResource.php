<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\CentMaterialResource\Pages;
use App\Models\CentClase;
use App\Models\CentMaterial;
use App\Models\Comision;
use App\Services\Cent\FilamentSedeScope;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentMaterialResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = CentMaterial::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';
    protected static ?string $navigationGroup = 'Aula virtual';
    protected static ?string $navigationLabel = 'Materiales';
    protected static ?string $modelLabel = 'material';
    protected static ?string $pluralModelLabel = 'materiales';
    protected static ?string $slug = 'materiales';
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
            Forms\Components\Select::make('clase_id')
                ->label('Clase')
                ->options(fn () => CentClase::latest('fecha_inicio')->pluck('titulo', 'id'))
                ->searchable(),
            Forms\Components\TextInput::make('titulo')->required()->maxLength(255),
            Forms\Components\Select::make('tipo')->options([
                'apunte' => 'Apunte',
                'video' => 'Video',
                'link' => 'Link',
                'presentacion' => 'Presentación',
                'guia' => 'Guía',
                'otro' => 'Otro',
            ])->default('apunte')->required()->native(false),
            Forms\Components\FileUpload::make('archivo')->disk('local')->directory('cent/materiales')->helperText('Archivo privado. La descarga se realiza desde acciones o el aula.'),
            Forms\Components\TextInput::make('url')->label('URL externa')->url()->maxLength(255),
            Forms\Components\Toggle::make('publicado')->default(true),
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
                Tables\Columns\TextColumn::make('tipo')->badge()->color('info'),
                Tables\Columns\IconColumn::make('archivo')->label('Archivo')->boolean()->getStateUsing(fn ($record) => filled($record->archivo)),
                Tables\Columns\IconColumn::make('url')->label('Link')->boolean()->getStateUsing(fn ($record) => filled($record->url)),
                Tables\Columns\ToggleColumn::make('publicado'),
                Tables\Columns\TextColumn::make('created_at')->label('Creado')->date('d/m/Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')->options([
                    'apunte' => 'Apunte',
                    'video' => 'Video',
                    'link' => 'Link',
                    'presentacion' => 'Presentación',
                    'guia' => 'Guía',
                    'otro' => 'Otro',
                ]),
                Tables\Filters\TernaryFilter::make('publicado'),
            ])
            ->actions([
                Tables\Actions\Action::make('archivo')
                    ->label('Archivo')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn (CentMaterial $record) => filled($record->archivo))
                    ->url(fn (CentMaterial $record) => route('cent.archivos.materiales', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
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
            'index' => Pages\ListCentMaterials::route('/'),
            'create' => Pages\CreateCentMaterial::route('/create'),
            'edit' => Pages\EditCentMaterial::route('/{record}/edit'),
        ];
    }
}
