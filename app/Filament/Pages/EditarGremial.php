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

class EditarGremial extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $navigationGroup = 'Prensa y web pública';
    protected static ?string $navigationLabel = 'Gremial';
    protected static ?string $title = 'Editar - Pagina Gremial';
    protected static string $view = 'filament.pages.editar-gremial';
    protected static ?string $slug = 'editar-gremial';
    protected static ?int $navigationSort = 3;
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $hero = PageSection::query()->where('page', 'gremial')->where('key', 'hero')->first();
        $intro = PageSection::query()->where('page', 'gremial')->where('key', 'intro')->first();

        $this->form->fill([
            'hero_title' => $hero?->title ?? 'Defensa de derechos laborales',
            'hero_subtitle' => $hero?->subtitle ?? 'Paritarias, comunicados, representacion y asesoramiento para los trabajadores de la sanidad en Tucuman.',
            'hero_image' => $hero?->image_path,
            'intro_title' => $intro?->title ?? 'Lo que hacemos por los trabajadores de la sanidad',
            'intro_subtitle' => $intro?->subtitle ?? 'ATSA Tucuman defiende los derechos laborales del sector salud con presencia, negociacion y acompanamiento real.',
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
                                        TextInput::make('hero_title')->label('Titulo principal')->required()->maxLength(150),
                                        Textarea::make('hero_subtitle')->label('Subtitulo')->rows(2)->maxLength(300),
                                    ]),
                                Section::make('Imagen de fondo')
                                    ->schema([
                                        FileUpload::make('hero_image')
                                            ->label('Foto de fondo del banner')
                                            ->image()
                                            ->imageEditor()
                                            ->disk('public')
                                            ->directory('page-sections/gremial')
                                            ->visibility('public')
                                            ->maxSize(4096),
                                    ]),
                            ]),
                        Tab::make('Seccion central')
                            ->schema([
                                Section::make('Titulo del bloque gremial')
                                    ->schema([
                                        TextInput::make('intro_title')->label('Titulo')->maxLength(200),
                                        Textarea::make('intro_subtitle')->label('Subtitulo / descripcion')->rows(3)->maxLength(400),
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
            'title' => $data['hero_title'],
            'subtitle' => $data['hero_subtitle'],
            'active' => true,
            'orden' => 1,
        ];

        if ($data['hero_image'] !== null) {
            $heroData['image_path'] = $data['hero_image'];
        }

        PageSection::updateOrCreate(['page' => 'gremial', 'key' => 'hero'], $heroData);

        PageSection::updateOrCreate(
            ['page' => 'gremial', 'key' => 'intro'],
            [
                'title' => $data['intro_title'],
                'subtitle' => $data['intro_subtitle'],
                'active' => true,
                'orden' => 2,
            ]
        );

        Notification::make()->title('Pagina Gremial actualizada')->success()->send();
    }
}
