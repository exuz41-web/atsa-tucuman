<?php

namespace App\Filament\Pages;

use App\Models\PageSection;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class EditarInicio extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Prensa y web pública';
    protected static ?string $navigationLabel = 'Inicio';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'Editor de sitio';
    protected static string $view = 'filament.pages.editar-inicio';
    protected static ?string $slug = 'editar-inicio';
    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public function mount(): void
    {
        $hero = PageSection::query()->where('page', 'home')->where('key', 'hero')->first();
        $galeria = PageSection::query()->where('page', 'home')->where('key', 'galeria')->first();
        $turismoCta = PageSection::query()->where('page', 'home')->where('key', 'cta_turismo')->first();

        $this->form->fill([
            'hero_label' => $hero?->label ?? 'Sindicato de trabajadores de la sanidad',
            'hero_title' => $hero?->title ?? 'Representamos a los trabajadores de la salud de Tucuman',
            'hero_subtitle' => $hero?->subtitle ?? 'ATSA Tucuman defiende los derechos laborales del sector sanitario desde hace mas de 100 anos.',
            'hero_image' => $hero?->image_path,
            'hero_btn1_text' => $hero?->button_text ?? 'Conoce tus derechos',
            'hero_btn1_url' => $hero?->button_url ?? '/gremial',
            'hero_btn2_text' => $hero?->secondary_button_text ?? 'Quiero afiliarme',
            'hero_btn2_url' => $hero?->secondary_button_url ?? '/afiliacion',
            'galeria_title' => $galeria?->title ?? 'Ciudad Deportiva y sede gremial',
            'galeria_subtitle' => $galeria?->subtitle ?? 'Un espacio propio para el deporte, la recreacion y la vida familiar de nuestros afiliados.',
            'turismo_title' => $turismoCta?->title ?? 'Descanso, deporte y convenios para afiliados',
            'turismo_subtitle' => $turismoCta?->subtitle ?? 'Conoce la Ciudad Deportiva ATSA, el Hotel ATSA en Termas de Rio Hondo y la red de hoteles y espacios recreativos vinculados a FATSA.',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Secciones')
                    ->tabs([
                        Tab::make('Banner principal')
                            ->schema([
                                Section::make('Texto del banner')
                                    ->schema([
                                        TextInput::make('hero_label')->label('Etiqueta pequena')->maxLength(100),
                                        TextInput::make('hero_title')->label('Titulo principal')->required()->maxLength(180),
                                        Textarea::make('hero_subtitle')->label('Subtitulo')->rows(3)->maxLength(400),
                                        TextInput::make('hero_btn1_text')->label('Boton principal - texto')->maxLength(80),
                                        TextInput::make('hero_btn1_url')->label('Boton principal - enlace')->maxLength(255),
                                        TextInput::make('hero_btn2_text')->label('Boton secundario - texto')->maxLength(80),
                                        TextInput::make('hero_btn2_url')->label('Boton secundario - enlace')->maxLength(255),
                                    ])->columns(2),
                                Section::make('Imagen de fondo')
                                    ->schema([
                                        FileUpload::make('hero_image')
                                            ->label('Foto de fondo del banner')
                                            ->image()
                                            ->imageEditor()
                                            ->disk('public')
                                            ->directory('page-sections/home')
                                            ->visibility('public')
                                            ->maxSize(4096),
                                    ]),
                            ]),
                        Tab::make('Galeria')
                            ->schema([
                                Section::make('Titulo de la seccion galeria')
                                    ->schema([
                                        TextInput::make('galeria_title')->label('Titulo')->maxLength(150),
                                        Textarea::make('galeria_subtitle')->label('Descripcion')->rows(2)->maxLength(300),
                                    ]),
                            ]),
                        Tab::make('Bloque Turismo')
                            ->schema([
                                Section::make('Texto del bloque de turismo')
                                    ->schema([
                                        TextInput::make('turismo_title')->label('Titulo')->maxLength(150),
                                        Textarea::make('turismo_subtitle')->label('Descripcion')->rows(3)->maxLength(400),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')->label('Guardar cambios')->action('save')->icon('heroicon-o-check')->color('primary'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $heroData = [
            'label' => $data['hero_label'],
            'title' => $data['hero_title'],
            'subtitle' => $data['hero_subtitle'],
            'button_text' => $data['hero_btn1_text'],
            'button_url' => $data['hero_btn1_url'],
            'secondary_button_text' => $data['hero_btn2_text'],
            'secondary_button_url' => $data['hero_btn2_url'],
            'active' => true,
            'orden' => 1,
        ];

        if ($data['hero_image'] !== null) {
            $heroData['image_path'] = $data['hero_image'];
        }

        PageSection::updateOrCreate(['page' => 'home', 'key' => 'hero'], $heroData);

        PageSection::updateOrCreate(
            ['page' => 'home', 'key' => 'galeria'],
            [
                'title' => $data['galeria_title'],
                'subtitle' => $data['galeria_subtitle'],
                'active' => true,
                'orden' => 2,
            ]
        );

        PageSection::updateOrCreate(
            ['page' => 'home', 'key' => 'cta_turismo'],
            [
                'title' => $data['turismo_title'],
                'subtitle' => $data['turismo_subtitle'],
                'active' => true,
                'orden' => 3,
            ]
        );

        Notification::make()->title('Pagina de Inicio actualizada')->success()->send();
    }
}
