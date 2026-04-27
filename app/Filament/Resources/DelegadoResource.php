<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\DelegadoResource\Pages;
use App\Models\Delegado;
use App\Models\Filial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DelegadoResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = Delegado::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Gremial';

    protected static ?string $navigationLabel = 'Delegados';

    protected static ?string $modelLabel = 'delegado';

    protected static ?string $pluralModelLabel = 'delegados';

    protected static ?string $slug = 'delegados';

    protected static ?int $navigationSort = 1;

    protected static ?string $panelScope = 'admin';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del delegado')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('nombre')
                        ->label('Nombre y apellido')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('filial_id')
                        ->label('Filial')
                        ->relationship('filial', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Forms\Components\TextInput::make('sector')
                        ->label('Sector / Lugar de trabajo')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('telefono')
                        ->label('Teléfono')
                        ->tel()
                        ->maxLength(80),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255),

                    Forms\Components\Toggle::make('activo')
                        ->label('Activo')
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('foto')
                        ->label('Foto')
                        ->image()
                        ->imageEditor()
                        ->directory('delegados')
                        ->disk('public')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('nombre')
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn (Delegado $r): string => 'https://ui-avatars.com/api/?name='.urlencode($r->nombre).'&background=dcfce7&color=15803d&size=80')
                    ->width(44)
                    ->height(44),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('filial.name')
                    ->label('Filial')
                    ->placeholder('—')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('sector')
                    ->label('Sector')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('filial_id')
                    ->label('Filial')
                    ->relationship('filial', 'name'),
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
            'index'  => Pages\ListDelegados::route('/'),
            'create' => Pages\CreateDelegado::route('/create'),
            'edit'   => Pages\EditDelegado::route('/{record}/edit'),
        ];
    }
}
