<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\AutoridadResource\Pages;
use App\Models\Autoridad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AutoridadResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = Autoridad::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Institucional y gremial';

    protected static ?string $navigationLabel = 'Autoridades';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'autoridad';

    protected static ?string $pluralModelLabel = 'autoridades';

    protected static ?string $panelScope = 'admin';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos principales')
                ->schema([
                    Forms\Components\TextInput::make('nombre')
                        ->label('Nombre completo')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('cargo')
                        ->label('Cargo')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\FileUpload::make('foto')
                        ->label('Foto')
                        ->disk('public')
                        ->directory('autoridades')
                        ->image()
                        ->imageEditor()
                        ->openable()
                        ->downloadable(),
                    Forms\Components\TextInput::make('orden')
                        ->label('Orden')
                        ->numeric()
                        ->default(0),
                    Forms\Components\Toggle::make('activo')
                        ->label('Activo')
                        ->default(true),
                    Forms\Components\Textarea::make('descripcion')
                        ->label('Descripción')
                        ->rows(5)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('orden')
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cargo')
                    ->label('Cargo')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('orden')
                    ->label('Orden')
                    ->sortable(),
                Tables\Columns\IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('activo')
                    ->label('Activo'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAutoridads::route('/'),
            'create' => Pages\CreateAutoridad::route('/create'),
            'edit' => Pages\EditAutoridad::route('/{record}/edit'),
        ];
    }
}
