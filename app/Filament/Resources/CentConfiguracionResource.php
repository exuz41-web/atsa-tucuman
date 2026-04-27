<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\CentConfiguracionResource\Pages;
use App\Models\CentConfiguracion;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentConfiguracionResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = CentConfiguracion::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $navigationLabel = 'Configuración CENT';
    protected static ?string $modelLabel = 'configuración';
    protected static ?string $pluralModelLabel = 'configuraciones';
    protected static ?string $slug = 'configuracion-cent';
    protected static ?string $panelScope = 'cent';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Parámetro')
                    ->schema([
                        Forms\Components\TextInput::make('clave')->required()->unique(ignoreRecord: true)->maxLength(255),
                        Forms\Components\TextInput::make('grupo')->required()->default('general')->maxLength(255),
                        Forms\Components\Select::make('tipo')
                            ->options([
                                'texto' => 'Texto',
                                'numero' => 'Número',
                                'email' => 'Email',
                                'telefono' => 'Teléfono',
                                'url' => 'URL',
                                'imagen' => 'Imagen',
                                'booleano' => 'Booleano',
                            ])
                            ->default('texto')
                            ->required()
                            ->native(false),
                        Forms\Components\Textarea::make('valor')->rows(4)->columnSpanFull(),
                        Forms\Components\TextInput::make('descripcion')->label('Descripción')->maxLength(255)->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('grupo')->badge()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('clave')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('valor')->limit(60)->searchable(),
                Tables\Columns\TextColumn::make('tipo')->badge(),
                Tables\Columns\TextColumn::make('updated_at')->label('Actualizado')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('grupo')->options(fn () => CentConfiguracion::query()->distinct()->pluck('grupo', 'grupo')->all()),
                Tables\Filters\SelectFilter::make('tipo')
                    ->options([
                        'texto' => 'Texto',
                        'numero' => 'Número',
                        'email' => 'Email',
                        'telefono' => 'Teléfono',
                        'url' => 'URL',
                        'imagen' => 'Imagen',
                        'booleano' => 'Booleano',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
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
            'index' => Pages\ListCentConfiguracions::route('/'),
            'create' => Pages\CreateCentConfiguracion::route('/create'),
            'edit' => Pages\EditCentConfiguracion::route('/{record}/edit'),
        ];
    }
}
