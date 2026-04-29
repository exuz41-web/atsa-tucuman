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

class EditarTurismo extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-sun';
    protected static ?string $navigationGroup = 'Prensa y web pública';
    protected static ?string $navigationLabel = 'Turismo';
    protected static ?string $title = 'Editar - Turismo y Recreacion';
    protected static string $view = 'filament.pages.editar-turismo';
    protected static ?string $slug = 'editar-turismo';
    protected static ?int $navigationSort = 4;
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $hero = PageSection::query()->where('page', 'turismo')->where('key', 'hero')->first();
        $ciudad = PageSection::query()->where('page', 'turismo')->where('key', 'ciudad_info')->first();
        $hotel = PageSection::query()->where('page', 'turismo')->where('key', 'hotel_info')->first();
        $condiciones = PageSection::query()->where('page', 'turismo')->where('key', 'condiciones')->first();

        $this->form->fill([
            'hero_title' => $hero?->title ?? 'Beneficios turisticos para la familia de la sanidad',
            'hero_subtitle' => $hero?->subtitle ?? 'Ciudad Deportiva ATSA, Hotel ATSA en Termas de Rio Hondo y convenios hoteleros nacionales a traves de FATSA.',
            'hero_image' => $hero?->image_path,
            'ciudad_title' => $ciudad?->title ?? 'Un predio para afiliados y sus familias',
            'ciudad_subtitle' => $ciudad?->subtitle ?? 'La Ciudad Deportiva ATSA cuenta con piletas, canchas, quinchos, asadores y salones.',
            'ciudad_horarios' => $ciudad?->body ?? 'Informes y reservas: lunes a viernes de 8:00 a 13:00 y de 15:00 a 18:00.',
            'hotel_title' => $hotel?->title ?? 'Hotel ATSA Tucuman en Termas de Rio Hondo',
            'hotel_subtitle' => $hotel?->subtitle ?? 'Un espacio pensado para que los afiliados puedan descansar, disfrutar de las aguas termales y compartir una escapada familiar.',
            'hotel_direccion' => $hotel?->button_text ?? 'M. Guemes, Termas de Rio Hondo, Santiago del Estero',
            'hotel_telefono' => $hotel?->button_url ?? '+54 3858 42-2005',
            'condiciones_title' => $condiciones?->title ?? 'Condiciones del beneficio',
            'condiciones_body' => $condiciones?->body ?? 'Los beneficios turisticos estan sujetos a disponibilidad, condicion de afiliado activo, reglamento vigente y confirmacion previa por parte de ATSA Tucuman.',
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
                                        TextInput::make('hero_title')->label('Titulo principal')->required()->maxLength(200),
                                        Textarea::make('hero_subtitle')->label('Subtitulo')->rows(2)->maxLength(350),
                                    ]),
                                Section::make('Imagen de fondo')
                                    ->schema([
                                        FileUpload::make('hero_image')
                                            ->label('Foto de fondo del banner')
                                            ->image()
                                            ->imageEditor()
                                            ->disk('public')
                                            ->directory('page-sections/turismo')
                                            ->visibility('public')
                                            ->maxSize(4096),
                                    ]),
                            ]),
                        Tab::make('Ciudad Deportiva')
                            ->schema([
                                Section::make('Descripcion de la Ciudad Deportiva')
                                    ->schema([
                                        TextInput::make('ciudad_title')->label('Titulo')->maxLength(150),
                                        Textarea::make('ciudad_subtitle')->label('Descripcion principal')->rows(3)->maxLength(500),
                                        TextInput::make('ciudad_horarios')->label('Horarios / informes')->maxLength(200),
                                    ]),
                            ]),
                        Tab::make('Hotel ATSA Termas')
                            ->schema([
                                Section::make('Informacion del Hotel ATSA')
                                    ->schema([
                                        TextInput::make('hotel_title')->label('Titulo')->maxLength(150),
                                        Textarea::make('hotel_subtitle')->label('Descripcion')->rows(3)->maxLength(500),
                                        TextInput::make('hotel_direccion')->label('Direccion')->maxLength(200),
                                        TextInput::make('hotel_telefono')->label('Telefono')->maxLength(50),
                                    ]),
                            ]),
                        Tab::make('Condiciones')
                            ->schema([
                                Section::make('Texto legal / aclaraciones')
                                    ->schema([
                                        TextInput::make('condiciones_title')->label('Titulo')->maxLength(150),
                                        RichEditor::make('condiciones_body')
                                            ->label('Texto')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList', 'redo', 'undo'])
                                            ->columnSpanFull(),
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

        PageSection::updateOrCreate(['page' => 'turismo', 'key' => 'hero'], $heroData);

        PageSection::updateOrCreate(
            ['page' => 'turismo', 'key' => 'ciudad_info'],
            [
                'title' => $data['ciudad_title'],
                'subtitle' => $data['ciudad_subtitle'],
                'body' => $data['ciudad_horarios'],
                'active' => true,
                'orden' => 2,
            ]
        );

        PageSection::updateOrCreate(
            ['page' => 'turismo', 'key' => 'hotel_info'],
            [
                'title' => $data['hotel_title'],
                'subtitle' => $data['hotel_subtitle'],
                'button_text' => $data['hotel_direccion'],
                'button_url' => $data['hotel_telefono'],
                'active' => true,
                'orden' => 3,
            ]
        );

        PageSection::updateOrCreate(
            ['page' => 'turismo', 'key' => 'condiciones'],
            [
                'title' => $data['condiciones_title'],
                'body' => $data['condiciones_body'],
                'active' => true,
                'orden' => 4,
            ]
        );

        Notification::make()->title('Pagina Turismo actualizada')->success()->send();
    }
}
