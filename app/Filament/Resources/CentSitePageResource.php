<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\CentSitePageResource\Pages;
use App\Models\SitePage;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CentSitePageResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = SitePage::class;
    protected static ?string $navigationIcon  = 'heroicon-o-paint-brush';
    protected static ?string $navigationGroup = 'Web CENT';
    protected static ?string $navigationLabel = 'Páginas del sitio';

    protected static ?int $navigationSort = 10;
    protected static ?string $modelLabel      = 'página';
    protected static ?string $pluralModelLabel = 'páginas del sitio';
    protected static ?string $panelScope = 'cent';

    // ──────────────────────────────────────────────────────────────────────────
    // FORM
    // ──────────────────────────────────────────────────────────────────────────
    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Información de la página')
                ->columns(3)
                ->schema([
                    Select::make('slug')
                        ->label('Página')
                        ->options(fn () => collect(SitePage::CENT_PAGES)
                            ->mapWithKeys(fn ($page, $slug) => [$slug => $page['label']])
                            ->toArray()
                        )
                        ->required()
                        ->native(false)
                        ->columnSpan(1)
                        ->disabled(fn (?SitePage $record) => $record !== null),
                    TextInput::make('label')
                        ->label('Nombre de la página')
                        ->required()
                        ->columnSpan(1),
                    Toggle::make('active')
                        ->label('Activa')
                        ->default(true)
                        ->columnSpan(1),
                ]),

            Section::make('Bloques de contenido')
                ->description('Arrastrá los bloques para reordenarlos. Podés agregar, quitar o desactivar cada sección sin perder su contenido.')
                ->schema([
                    Builder::make('blocks')
                        ->label('')
                        ->reorderable()
                        ->collapsible()
                        ->cloneable()
                        ->addActionLabel('+ Agregar bloque')
                        ->blocks([

                            // ── HERO ──────────────────────────────────────────
                            Block::make('hero')
                                ->label('🖼️  Hero / Banner principal')
                                ->icon('heroicon-o-photo')
                                ->schema([
                                    Toggle::make('visible')->label('Visible')->default(true)->inline(),
                                    Grid::make(2)->schema([
                                        TextInput::make('title')
                                            ->label('Título principal')
                                            ->required()
                                            ->maxLength(200),
                                        TextInput::make('subtitle')
                                            ->label('Subtítulo')
                                            ->maxLength(200),
                                    ]),
                                    FileUpload::make('background_image')
                                        ->label('Imagen de fondo')
                                        ->image()->imageEditor()
                                        ->disk('public')->directory('cent/pages')
                                        ->helperText('Recomendado: 1600×900px JPG o WebP')
                                        ->maxSize(5120)
                                        ->columnSpanFull(),
                                    Grid::make(2)->schema([
                                        TextInput::make('cta_text')->label('Texto del botón'),
                                        TextInput::make('cta_url')->label('URL del botón'),
                                    ]),
                                ]),

                            // ── STATS BAR ─────────────────────────────────────
                            Block::make('stats_bar')
                                ->label('📊  Barra de estadísticas')
                                ->icon('heroicon-o-chart-bar')
                                ->schema([
                                    Toggle::make('visible')->label('Visible')->default(true)->inline(),
                                    Repeater::make('items')
                                        ->label('Estadísticas')
                                        ->schema([
                                            Grid::make(3)->schema([
                                                TextInput::make('label')->label('Etiqueta')->required()->placeholder('Carreras'),
                                                TextInput::make('value')->label('Valor')->required()->placeholder('5'),
                                                TextInput::make('icon')->label('Ícono Tabler')->placeholder('ti ti-award'),
                                            ]),
                                        ])
                                        ->defaultItems(3)
                                        ->maxItems(6)
                                        ->reorderable()
                                        ->collapsible(),
                                ]),

                            // ── CARDS SECTION ─────────────────────────────────
                            Block::make('cards_section')
                                ->label('🃏  Sección de cards / pilares')
                                ->icon('heroicon-o-squares-2x2')
                                ->schema([
                                    Toggle::make('visible')->label('Visible')->default(true)->inline(),
                                    Grid::make(2)->schema([
                                        TextInput::make('title')->label('Título de la sección')->maxLength(150),
                                        TextInput::make('subtitle')->label('Subtítulo')->maxLength(300),
                                    ]),
                                    Repeater::make('items')
                                        ->label('Cards')
                                        ->schema([
                                            Grid::make(2)->schema([
                                                TextInput::make('icon')->label('Ícono Tabler')->placeholder('ti ti-heart'),
                                                TextInput::make('title')->label('Título')->required(),
                                            ]),
                                            Textarea::make('description')->label('Descripción')->rows(2),
                                        ])
                                        ->defaultItems(3)
                                        ->maxItems(8)
                                        ->reorderable()
                                        ->collapsible(),
                                ]),

                            // ── TEXT + IMAGE ───────────────────────────────────
                            Block::make('text_image')
                                ->label('📝  Texto con imagen')
                                ->icon('heroicon-o-document-text')
                                ->schema([
                                    Toggle::make('visible')->label('Visible')->default(true)->inline(),
                                    Grid::make(2)->schema([
                                        TextInput::make('title')->label('Título')->maxLength(200),
                                        Select::make('image_position')
                                            ->label('Imagen a la')
                                            ->options(['left'=>'Izquierda','right'=>'Derecha'])
                                            ->default('right'),
                                    ]),
                                    Textarea::make('text')
                                        ->label('Contenido / texto')
                                        ->rows(5)
                                        ->columnSpanFull(),
                                    FileUpload::make('image')
                                        ->label('Imagen')
                                        ->image()->imageEditor()
                                        ->disk('public')->directory('cent/pages')
                                        ->maxSize(3072),
                                ]),

                            // ── ACCORDION ─────────────────────────────────────
                            Block::make('accordion_section')
                                ->label('❓  Acordeón / preguntas frecuentes')
                                ->icon('heroicon-o-question-mark-circle')
                                ->schema([
                                    Toggle::make('visible')->label('Visible')->default(true)->inline(),
                                    TextInput::make('title')
                                        ->label('Título de la sección')
                                        ->maxLength(200),
                                    Repeater::make('items')
                                        ->label('Preguntas y respuestas')
                                        ->schema([
                                            TextInput::make('question')->label('Pregunta')->required(),
                                            Textarea::make('answer')->label('Respuesta')->rows(3)->required(),
                                        ])
                                        ->defaultItems(3)
                                        ->maxItems(20)
                                        ->reorderable()
                                        ->collapsible(),
                                ]),

                            // ── CTA SECTION ───────────────────────────────────
                            Block::make('cta_section')
                                ->label('🎯  Llamada a la acción (CTA)')
                                ->icon('heroicon-o-cursor-arrow-rays')
                                ->schema([
                                    Toggle::make('visible')->label('Visible')->default(true)->inline(),
                                    Grid::make(2)->schema([
                                        TextInput::make('title')->label('Título')->required()->maxLength(200),
                                        TextInput::make('subtitle')->label('Descripción')->maxLength(400),
                                    ]),
                                    Grid::make(2)->schema([
                                        TextInput::make('cta_text')->label('Texto del botón'),
                                        TextInput::make('cta_url')->label('URL del botón'),
                                    ]),
                                ]),

                            // ── CONTACT INFO ──────────────────────────────────
                            Block::make('contact_info')
                                ->label('📞  Información de contacto')
                                ->icon('heroicon-o-phone')
                                ->schema([
                                    Toggle::make('visible')->label('Visible')->default(true)->inline(),
                                    Grid::make(2)->schema([
                                        TextInput::make('title')->label('Título')->maxLength(150),
                                        TextInput::make('address')->label('Dirección')->maxLength(255),
                                    ]),
                                    Grid::make(2)->schema([
                                        TextInput::make('phone')->label('Teléfono')->maxLength(20),
                                        TextInput::make('email')->label('Email')->email()->maxLength(100),
                                    ]),
                                ]),

                            // ── CUSTOM HTML ───────────────────────────────────
                            Block::make('custom_html')
                                ->label('💻  HTML personalizado')
                                ->icon('heroicon-o-code-bracket')
                                ->schema([
                                    Toggle::make('visible')->label('Visible')->default(true)->inline(),
                                    Textarea::make('html')
                                        ->label('Contenido HTML')
                                        ->rows(8)
                                        ->columnSpanFull(),
                                ]),

                        ])
                        ->columnSpanFull(),
                ]),

        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // TABLE
    // ──────────────────────────────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->whereIn('slug', array_keys(SitePage::CENT_PAGES)))
            ->columns([
                TextColumn::make('label')
                    ->label('Página')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('blocks')
                    ->label('Bloques')
                    ->getStateUsing(fn (SitePage $r) => count($r->blocks ?? []).' bloques')
                    ->badge()
                    ->color('info'),
                IconColumn::make('active')
                    ->label('Activa')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Última edición')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('slug')
                    ->label('Página')
                    ->options(fn () => collect(SitePage::CENT_PAGES)
                        ->mapWithKeys(fn ($page, $slug) => [$slug => $page['label']])
                        ->toArray()
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('slug')
            ->striped();
    }

    // ──────────────────────────────────────────────────────────────────────────
    // NAVIGATION & ACCESS
    // ──────────────────────────────────────────────────────────────────────────
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
            'index'  => Pages\ListCentSitePages::route('/'),
            'create' => Pages\CreateCentSitePage::route('/create'),
            'edit'   => Pages\EditCentSitePage::route('/{record}/edit'),
        ];
    }
}
