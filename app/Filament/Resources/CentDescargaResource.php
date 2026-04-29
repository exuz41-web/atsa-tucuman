<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\CentDescargaResource\Pages;
use App\Models\CentDescarga;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentDescargaResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = CentDescarga::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static ?string $navigationGroup = 'Comunicación CENT';
    protected static ?string $modelLabel = 'descarga CENT';
    protected static ?string $pluralModelLabel = 'descargas CENT';
    protected static ?string $slug = 'descargas-cent';
    protected static ?int $navigationSort = 20;
    protected static ?string $panelScope = 'cent';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('titulo')
                ->label('Título')
                ->placeholder('Ej: Ficha de inscripción aspirantes 2026')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            Forms\Components\Select::make('categoria')
                ->label('Categoría')
                ->options(CentDescarga::categorias())
                ->required()
                ->native(false),

            Forms\Components\Select::make('carrera_id')
                ->label('Carrera')
                ->relationship('carrera', 'name')
                ->searchable()
                ->preload()
                ->helperText('Opcional. Si queda vacío, aplica a todas las carreras.'),

            Forms\Components\Textarea::make('descripcion')
                ->label('Descripción')
                ->rows(3)
                ->maxLength(500)
                ->columnSpanFull(),

            Forms\Components\FileUpload::make('archivo')
                ->label('Archivo')
                ->disk('public')
                ->directory('cent/descargas')
                ->visibility('public')
                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                ->maxSize(15360)
                ->downloadable()
                ->openable()
                ->helperText('PDF, imagen o Word. Máximo 15 MB.')
                ->columnSpanFull(),

            Forms\Components\TextInput::make('url_externa')
                ->label('URL externa (alternativa al archivo)')
                ->url()
                ->placeholder('https://...')
                ->helperText('Si el archivo está alojado externamente (Drive, etc.), pegá el link aquí.')
                ->columnSpanFull(),

            Forms\Components\TextInput::make('orden')
                ->label('Orden')
                ->numeric()
                ->default(0)
                ->helperText('Menor número aparece primero.'),

            Forms\Components\Toggle::make('activo')
                ->label('Publicado')
                ->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\BadgeColumn::make('categoria')
                    ->label('Categoría')
                    ->formatStateUsing(fn (string $state): string => CentDescarga::categorias()[$state] ?? ucfirst($state))
                    ->colors([
                        'primary'   => 'formularios',
                        'warning'   => 'reglamentos',
                        'success'   => 'planes_estudio',
                        'info'      => 'inscripciones',
                        'danger'    => 'resoluciones',
                        'secondary' => 'otros',
                    ]),
                Tables\Columns\TextColumn::make('carrera.name')
                    ->label('Carrera')
                    ->placeholder('Todas')
                    ->limit(25),
                Tables\Columns\IconColumn::make('archivo')
                    ->label('Archivo')
                    ->boolean()
                    ->trueIcon('heroicon-o-paper-clip')
                    ->falseIcon('heroicon-o-link'),
                Tables\Columns\ToggleColumn::make('activo')
                    ->label('Publicado'),
                Tables\Columns\TextColumn::make('orden')
                    ->label('Orden')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('orden')
            ->filters([
                Tables\Filters\SelectFilter::make('categoria')
                    ->label('Categoría')
                    ->options(CentDescarga::categorias()),
                Tables\Filters\SelectFilter::make('carrera_id')
                    ->label('Carrera')
                    ->relationship('carrera', 'name'),
                Tables\Filters\TernaryFilter::make('activo')
                    ->label('Publicado'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCentDescargas::route('/'),
            'create' => Pages\CreateCentDescarga::route('/create'),
            'edit'   => Pages\EditCentDescarga::route('/{record}/edit'),
        ];
    }
}
