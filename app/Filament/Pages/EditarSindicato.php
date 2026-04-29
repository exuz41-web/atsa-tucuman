<?php

namespace App\Filament\Pages;

use App\Models\PageSection;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
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

class EditarSindicato extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Prensa y web pública';
    protected static ?string $navigationLabel = 'El Sindicato';
    protected static ?string $title = 'Editar - El Sindicato';
    protected static string $view = 'filament.pages.editar-sindicato';
    protected static ?string $slug = 'editar-sindicato';
    protected static ?int $navigationSort = 2;
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $hero = PageSection::query()->where('page', 'sindicato')->where('key', 'hero')->first();
        $historia = PageSection::query()->where('page', 'sindicato')->where('key', 'historia')->first();
        $mision = PageSection::query()->where('page', 'sindicato')->where('key', 'mision')->first();

        $this->form->fill([
            'hero_label' => $hero?->label ?? 'INSTITUCIONAL',
            'hero_title' => $hero?->title ?? 'El Sindicato',
            'hero_subtitle' => $hero?->subtitle ?? '100 anos defendiendo a la sanidad tucumana',
            'hero_image' => $hero?->image_path,
            'historia_title' => $historia?->title ?? '100 anos de historia gremial',
            'historia_body' => $historia?->body ?? '',
            'mision_title' => $mision?->title ?? 'Lo que guia nuestra tarea',
            'mision_subtitle' => $mision?->subtitle ?? 'Principios fundamentales que sostienen nuestro compromiso con los trabajadores',
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
                                        TextInput::make('hero_label')->label('Etiqueta pequena')->maxLength(80),
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
                                            ->directory('page-sections/sindicato')
                                            ->visibility('public')
                                            ->maxSize(4096),
                                    ]),
                            ]),
                        Tab::make('Historia')
                            ->schema([
                                Section::make('Seccion Historia del Gremio')
                                    ->schema([
                                        TextInput::make('historia_title')->label('Titulo de la historia')->maxLength(150),
                                        RichEditor::make('historia_body')
                                            ->label('Texto de la historia')
                                            ->toolbarButtons(['bold', 'italic', 'underline', 'bulletList', 'orderedList', 'h2', 'h3', 'redo', 'undo'])
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Mision y Valores')
                            ->schema([
                                Section::make('Titulo de la seccion')
                                    ->schema([
                                        TextInput::make('mision_title')->label('Titulo')->maxLength(150),
                                        Textarea::make('mision_subtitle')->label('Descripcion')->rows(2)->maxLength(300),
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
            'active' => true,
            'orden' => 1,
        ];

        if ($data['hero_image'] !== null) {
            $heroData['image_path'] = $data['hero_image'];
        }

        PageSection::updateOrCreate(['page' => 'sindicato', 'key' => 'hero'], $heroData);

        PageSection::updateOrCreate(
            ['page' => 'sindicato', 'key' => 'historia'],
            [
                'title' => $data['historia_title'],
                'body' => $data['historia_body'],
                'active' => true,
                'orden' => 2,
            ]
        );

        PageSection::updateOrCreate(
            ['page' => 'sindicato', 'key' => 'mision'],
            [
                'title' => $data['mision_title'],
                'subtitle' => $data['mision_subtitle'],
                'active' => true,
                'orden' => 3,
            ]
        );

        Notification::make()->title('Pagina El Sindicato actualizada')->success()->send();
    }
}
