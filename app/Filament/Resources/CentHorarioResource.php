<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\CentHorarioResource\Pages;
use App\Models\CentHorario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentHorarioResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = CentHorario::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Contenido diario – CENT';
    protected static ?string $modelLabel = 'horario de cursado';
    protected static ?string $pluralModelLabel = 'horarios de cursado';
    protected static ?string $slug = 'horarios-cent';
    protected static ?int $navigationSort = 5;
    protected static ?string $panelScope = 'cent';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('titulo')
                ->label('Título')
                ->placeholder('Ej: Horarios Enfermería Capital 2026')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            Forms\Components\Select::make('cent_sede_id')
                ->label('Sede')
                ->relationship('sede', 'nombre')
                ->searchable()
                ->preload()
                ->helperText('Opcional. Si queda vacío, aplica a todas las sedes.'),

            Forms\Components\Select::make('carrera_id')
                ->label('Carrera')
                ->relationship('carrera', 'name')
                ->searchable()
                ->preload()
                ->helperText('Opcional. Si queda vacío, aplica a todas las carreras.'),

            Forms\Components\TextInput::make('ciclo_lectivo')
                ->label('Ciclo lectivo')
                ->placeholder('Ej: 2026')
                ->maxLength(20),

            Forms\Components\RichEditor::make('descripcion')
                ->label('Descripción / Tabla de horarios')
                ->helperText('Podés escribir la tabla de horarios directamente aquí.')
                ->toolbarButtons(['bold', 'italic', 'underline', 'bulletList', 'orderedList', 'table', 'undo', 'redo'])
                ->columnSpanFull(),

            Forms\Components\FileUpload::make('archivo')
                ->label('Archivo (PDF o imagen)')
                ->disk('public')
                ->directory('cent/horarios')
                ->visibility('public')
                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                ->maxSize(8192)
                ->downloadable()
                ->openable()
                ->helperText('PDF o imagen del horario. Opcional si ya completaste la tabla de arriba.')
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
                Tables\Columns\TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->placeholder('Todas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('carrera.name')
                    ->label('Carrera')
                    ->placeholder('Todas')
                    ->limit(30),
                Tables\Columns\TextColumn::make('ciclo_lectivo')
                    ->label('Ciclo')
                    ->sortable(),
                Tables\Columns\IconColumn::make('archivo')
                    ->label('Archivo')
                    ->boolean()
                    ->trueIcon('heroicon-o-paper-clip')
                    ->falseIcon('heroicon-o-minus'),
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
                Tables\Filters\SelectFilter::make('cent_sede_id')
                    ->label('Sede')
                    ->relationship('sede', 'nombre'),
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
            'index'  => Pages\ListCentHorarios::route('/'),
            'create' => Pages\CreateCentHorario::route('/create'),
            'edit'   => Pages\EditCentHorario::route('/{record}/edit'),
        ];
    }
}
