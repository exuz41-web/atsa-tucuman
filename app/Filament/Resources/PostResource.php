<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Gestión web';

    protected static ?string $navigationLabel = 'Noticias y novedades';

    protected static ?string $modelLabel = 'novedad';

    protected static ?string $pluralModelLabel = 'novedades';

    protected static ?string $slug = 'posts';

    protected static ?int $navigationSort = 1;

    protected static ?string $panelScope = 'admin';

    public static function getNavigationBadge(): ?string
    {
        return (string) Post::whereNull('published_at')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'gray';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(3)->schema([

                Forms\Components\Group::make()->columnSpan(2)->schema([

                    Forms\Components\Section::make('Contenido')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Título')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),

                            Forms\Components\TextInput::make('slug')
                                ->label('Slug (URL)')
                                ->required()
                                ->maxLength(255)
                                ->unique(Post::class, 'slug', ignoreRecord: true)
                                ->rules(['alpha_dash']),

                            Forms\Components\Textarea::make('excerpt')
                                ->label('Bajada / Resumen')
                                ->rows(3)
                                ->maxLength(500)
                                ->helperText('Texto introductorio que se muestra en los listados.'),

                            Forms\Components\RichEditor::make('body')
                                ->label('Cuerpo de la nota')
                                ->toolbarButtons([
                                    'bold', 'italic', 'underline', 'strike',
                                    'bulletList', 'orderedList',
                                    'h2', 'h3',
                                    'link', 'blockquote',
                                    'redo', 'undo',
                                ])
                                ->columnSpanFull(),
                        ]),

                    Forms\Components\Section::make('Multimedia')
                        ->schema([
                            Forms\Components\FileUpload::make('image')
                                ->label('Imagen principal')
                                ->image()
                                ->imageEditor()
                                ->directory('posts')
                                ->disk('public')
                                ->helperText('Se aplica marca de agua automáticamente al guardar.')
                                ->columnSpanFull(),

                            Forms\Components\FileUpload::make('gallery')
                                ->label('Galería')
                                ->image()
                                ->multiple()
                                ->reorderable()
                                ->directory('posts/gallery')
                                ->disk('public')
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('video_url')
                                ->label('URL de video (YouTube / Vimeo)')
                                ->url()
                                ->placeholder('https://www.youtube.com/watch?v=...')
                                ->maxLength(500),
                        ]),

                    Forms\Components\Section::make('Fuente')
                        ->columns(2)
                        ->collapsed()
                        ->schema([
                            Forms\Components\TextInput::make('fuente')
                                ->label('Fuente')
                                ->maxLength(255),

                            Forms\Components\TextInput::make('fuente_url')
                                ->label('URL de la fuente')
                                ->url()
                                ->maxLength(500),
                        ]),
                ]),

                Forms\Components\Group::make()->columnSpan(1)->schema([

                    Forms\Components\Section::make('Publicación')
                        ->schema([
                            Forms\Components\Select::make('category')
                                ->label('Categoría')
                                ->options(self::categorias())
                                ->required()
                                ->native(false),

                            Forms\Components\DateTimePicker::make('published_at')
                                ->label('Fecha de publicación')
                                ->displayFormat('d/m/Y H:i')
                                ->helperText('Dejá vacío para guardar como borrador.'),

                            Forms\Components\Select::make('author_id')
                                ->label('Autor')
                                ->relationship('author', 'name')
                                ->searchable()
                                ->preload()
                                ->placeholder('Sin autor asignado'),

                            Forms\Components\Toggle::make('destacado')
                                ->label('Destacar')
                                ->helperText('Aparece en la sección de noticias destacadas.'),
                        ]),

                    Forms\Components\Section::make('SEO y etiquetas')
                        ->collapsed()
                        ->schema([
                            Forms\Components\TagsInput::make('tags')
                                ->label('Etiquetas')
                                ->placeholder('Agregar etiqueta...'),

                            Forms\Components\Textarea::make('meta_description')
                                ->label('Meta descripción')
                                ->rows(3)
                                ->maxLength(160)
                                ->helperText('Máximo 160 caracteres para Google.'),
                        ]),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('')
                    ->width(64)
                    ->height(44)
                    ->defaultImageUrl(asset('images/placeholder.png')),

                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(55),

                Tables\Columns\BadgeColumn::make('category')
                    ->label('Categoría')
                    ->formatStateUsing(fn (?string $state): string => self::categorias()[$state] ?? ucfirst((string) $state))
                    ->color(fn (?string $state): string => match ($state) {
                        'gremial'        => 'primary',
                        'institucional'  => 'info',
                        'accion_social'  => 'danger',
                        'turismo'        => 'success',
                        'formacion'      => 'warning',
                        default          => 'gray',
                    }),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Autor')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publicada')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Borrador')
                    ->sortable(),

                Tables\Columns\IconColumn::make('destacado')
                    ->label('★')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Categoría')
                    ->options(self::categorias()),

                Tables\Filters\TernaryFilter::make('publicada')
                    ->label('Estado')
                    ->placeholder('Todas')
                    ->trueLabel('Publicadas')
                    ->falseLabel('Borradores')
                    ->queries(
                        true: fn (Builder $q) => $q->whereNotNull('published_at'),
                        false: fn (Builder $q) => $q->whereNull('published_at'),
                    ),

                Tables\Filters\TernaryFilter::make('destacado')
                    ->label('Destacada'),
            ])
            ->actions([
                Tables\Actions\Action::make('publicar')
                    ->label('Publicar')
                    ->icon('heroicon-o-arrow-up-circle')
                    ->color('success')
                    ->visible(fn (Post $r) => is_null($r->published_at))
                    ->requiresConfirmation()
                    ->action(fn (Post $r) => $r->update(['published_at' => now()])),

                Tables\Actions\Action::make('despublicar')
                    ->label('Despublicar')
                    ->icon('heroicon-o-arrow-down-circle')
                    ->color('gray')
                    ->visible(fn (Post $r) => ! is_null($r->published_at))
                    ->requiresConfirmation()
                    ->action(fn (Post $r) => $r->update(['published_at' => null])),

                Tables\Actions\Action::make('destacar')
                    ->label(fn (Post $r) => $r->destacado ? 'Quitar destaque' : 'Destacar')
                    ->icon('heroicon-o-star')
                    ->color(fn (Post $r) => $r->destacado ? 'gray' : 'warning')
                    ->action(fn (Post $r) => $r->update(['destacado' => ! $r->destacado])),

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
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit'   => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function categorias(): array
    {
        return [
            'gremial'       => 'Gremial',
            'institucional' => 'Institucional',
            'accion_social' => 'Acción social',
            'turismo'       => 'Turismo',
            'formacion'     => 'Formación',
            'convenio'      => 'Convenio',
            'general'       => 'General',
        ];
    }
}
