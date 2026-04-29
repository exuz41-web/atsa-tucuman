<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\BeneficioResource\Pages;
use App\Models\Beneficio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BeneficioResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = Beneficio::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Secretarías y beneficios';

    protected static ?string $navigationLabel = 'Beneficios';

    protected static ?string $modelLabel = 'beneficio';

    protected static ?string $pluralModelLabel = 'beneficios';

    protected static ?string $slug = 'beneficios';

    protected static ?int $navigationSort = 10;

    protected static ?string $panelScope = 'admin';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Información general')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('titulo')
                        ->label('Título')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state)))
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug (URL)')
                        ->required()
                        ->maxLength(255)
                        ->unique(Beneficio::class, 'slug', ignoreRecord: true)
                        ->rules(['alpha_dash']),

                    Forms\Components\Select::make('categoria')
                        ->label('Categoría')
                        ->options(Beneficio::categorias())
                        ->required()
                        ->native(false),

                    Forms\Components\TextInput::make('orden')
                        ->label('Orden')
                        ->numeric()
                        ->default(0),

                    Forms\Components\TextInput::make('icono')
                        ->label('Icono (clase Tabler)')
                        ->placeholder('ti-gift')
                        ->maxLength(80),
                ]),

            Forms\Components\Section::make('Visibilidad')
                ->columns(3)
                ->schema([
                    Forms\Components\Toggle::make('activo')
                        ->label('Activo')
                        ->helperText('El beneficio existe en el sistema.'),

                    Forms\Components\Toggle::make('publico')
                        ->label('Público')
                        ->helperText('Visible para visitantes no logueados.'),

                    Forms\Components\Toggle::make('solo_afiliados')
                        ->label('Solo afiliados')
                        ->helperText('Se requiere estar afiliado para acceder.'),

                    Forms\Components\Toggle::make('destacado')
                        ->label('Destacado')
                        ->helperText('Aparece en la sección de beneficios destacados.')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Contenido')
                ->schema([
                    Forms\Components\Textarea::make('descripcion_corta')
                        ->label('Descripción corta')
                        ->rows(3)
                        ->maxLength(500)
                        ->helperText('Se muestra en listados y tarjetas. Máximo 500 caracteres.')
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('descripcion_larga')
                        ->label('Descripción larga')
                        ->toolbarButtons([
                            'bold', 'italic', 'underline', 'strike',
                            'bulletList', 'orderedList',
                            'h2', 'h3',
                            'link', 'blockquote',
                        ])
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('requisitos')
                        ->label('Requisitos')
                        ->rows(4)
                        ->helperText('Requisitos para acceder al beneficio.')
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('documentacion')
                        ->label('Documentación requerida')
                        ->rows(4)
                        ->helperText('Documentos que debe presentar el afiliado.')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Imagen y enlace')
                ->columns(2)
                ->schema([
                    Forms\Components\FileUpload::make('imagen')
                        ->label('Imagen')
                        ->image()
                        ->imageEditor()
                        ->directory('beneficios')
                        ->disk('public')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('link')
                        ->label('Enlace externo')
                        ->url()
                        ->placeholder('https://')
                        ->maxLength(500)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('orden')
            ->reorderable('orden')
            ->columns([
                Tables\Columns\ImageColumn::make('imagen')
                    ->label('')
                    ->width(56)
                    ->height(40)
                    ->defaultImageUrl(asset('images/placeholder.png')),

                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('categoria')
                    ->label('Categoría')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => Beneficio::categorias()[$state] ?? ucfirst((string) $state))
                    ->color(fn (?string $state): string => Beneficio::colores()[$state] ?? 'gray'),

                Tables\Columns\IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),

                Tables\Columns\IconColumn::make('destacado')
                    ->label('Destacado')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                Tables\Columns\IconColumn::make('publico')
                    ->label('Público')
                    ->boolean(),

                Tables\Columns\TextColumn::make('orden')
                    ->label('Orden')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categoria')
                    ->label('Categoría')
                    ->options(Beneficio::categorias()),
                Tables\Filters\TernaryFilter::make('activo')
                    ->label('Activo'),
                Tables\Filters\TernaryFilter::make('destacado')
                    ->label('Destacado'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggle_activo')
                    ->label(fn (Beneficio $r) => $r->activo ? 'Desactivar' : 'Activar')
                    ->icon(fn (Beneficio $r) => $r->activo ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn (Beneficio $r) => $r->activo ? 'gray' : 'success')
                    ->action(fn (Beneficio $r) => $r->update(['activo' => ! $r->activo])),

                Tables\Actions\Action::make('toggle_destacado')
                    ->label(fn (Beneficio $r) => $r->destacado ? 'Quitar destaque' : 'Destacar')
                    ->icon('heroicon-o-star')
                    ->color(fn (Beneficio $r) => $r->destacado ? 'gray' : 'warning')
                    ->action(fn (Beneficio $r) => $r->update(['destacado' => ! $r->destacado])),

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
            'index'  => Pages\ListBeneficios::route('/'),
            'create' => Pages\CreateBeneficio::route('/create'),
            'edit'   => Pages\EditBeneficio::route('/{record}/edit'),
        ];
    }
}
