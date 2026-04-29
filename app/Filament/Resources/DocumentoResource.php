<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\DocumentoResource\Pages;
use App\Models\Documento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentoResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = Documento::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Institucional y gremial';

    protected static ?string $navigationLabel = 'Documentos';

    protected static ?string $modelLabel = 'documento';

    protected static ?string $pluralModelLabel = 'documentos';

    protected static ?string $slug = 'documentos';

    protected static ?int $navigationSort = 40;

    protected static ?string $panelScope = 'admin';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del documento')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('titulo')
                        ->label('Título')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('tipo')
                        ->label('Tipo')
                        ->options(self::tipos())
                        ->required()
                        ->native(false),

                    Forms\Components\TextInput::make('anio')
                        ->label('Año')
                        ->numeric()
                        ->minValue(1900)
                        ->maxValue(2099)
                        ->default(now()->year),

                    Forms\Components\Toggle::make('activo')
                        ->label('Activo / Publicado')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Archivo')
                ->schema([
                    Forms\Components\FileUpload::make('archivo')
                        ->label('Archivo PDF')
                        ->acceptedFileTypes(['application/pdf'])
                        ->directory('documentos')
                        ->disk('public')
                        ->downloadable()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('anio', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tipo')
                    ->label('Tipo')
                    ->formatStateUsing(fn (?string $state): string => self::tipos()[$state] ?? ucfirst((string) $state))
                    ->color('info'),

                Tables\Columns\TextColumn::make('anio')
                    ->label('Año')
                    ->sortable(),

                Tables\Columns\IconColumn::make('archivo')
                    ->label('PDF')
                    ->boolean()
                    ->trueIcon('heroicon-o-document')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options(self::tipos()),
                Tables\Filters\TernaryFilter::make('activo')->label('Activo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
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
            'index'  => Pages\ListDocumentos::route('/'),
            'create' => Pages\CreateDocumento::route('/create'),
            'edit'   => Pages\EditDocumento::route('/{record}/edit'),
        ];
    }

    public static function tipos(): array
    {
        return [
            'estatuto'         => 'Estatuto',
            'convenio'         => 'Convenio colectivo',
            'resolucion'       => 'Resolución',
            'circular'         => 'Circular',
            'acta'             => 'Acta',
            'informe'          => 'Informe',
            'escala_salarial'  => 'Escala salarial',
            'otro'             => 'Otro',
        ];
    }
}
