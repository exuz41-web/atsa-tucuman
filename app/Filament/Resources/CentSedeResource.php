<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\CentSedeResource\Pages;
use App\Models\CentSede;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CentSedeResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = CentSede::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'CENT N°74';
    protected static ?string $modelLabel = 'sede CENT';
    protected static ?string $pluralModelLabel = 'sedes CENT';
    protected static ?string $panelScope = 'cent';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nombre')->required()->live(onBlur: true)->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('ciudad')->required(),
            Forms\Components\TextInput::make('direccion')->label('Dirección'),
            Forms\Components\TextInput::make('telefono')->label('Teléfono'),
            Forms\Components\TextInput::make('whatsapp'),
            Forms\Components\TextInput::make('email')->email(),
            Forms\Components\Textarea::make('horarios')->label('Horarios')->rows(3),
            Forms\Components\TextInput::make('responsable'),
            Forms\Components\FileUpload::make('imagen')->image()->disk('public')->directory('cent/sedes')->imageEditor(),
            Forms\Components\TextInput::make('orden')->label('Orden')->numeric()->default(0),
            Forms\Components\Toggle::make('activa')->label('Activa')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('imagen')->disk('public')->square(),
            Tables\Columns\TextColumn::make('nombre')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('ciudad')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('direccion')->label('Dirección')->limit(34)->toggleable(),
            Tables\Columns\TextColumn::make('telefono')->label('Teléfono'),
            Tables\Columns\ToggleColumn::make('activa')->label('Activa'),
            Tables\Columns\TextColumn::make('orden')->label('Orden')->sortable(),
        ])->filters([
            Tables\Filters\TernaryFilter::make('activa')->label('Activa'),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ])->bulkActions([
            Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
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
            'index' => Pages\ListCentSedes::route('/'),
            'create' => Pages\CreateCentSede::route('/create'),
            'edit' => Pages\EditCentSede::route('/{record}/edit'),
        ];
    }
}

