<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
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

class EditarContacto extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationGroup = 'Editor del sitio - ATSA';

    protected static ?string $navigationLabel = 'Contacto y datos generales';

    protected static ?string $title = 'Editar - Contacto y datos del sitio';

    protected static string $view = 'filament.pages.editar-contacto';

    protected static ?string $slug = 'editar-contacto';

    protected static ?int $navigationSort = 50;

    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $s = SiteSetting::current();

        $this->form->fill([
            'site_name' => $s->site_name ?? 'ATSA Tucuman',
            'address' => $s->address ?? 'Paraguay y Thames, San Miguel de Tucuman',
            'phone' => $s->phone ?? '0381 4331665',
            'whatsapp' => $s->whatsapp ?? '543814331665',
            'email' => $s->email ?? '',
            'schedule' => $s->schedule ?? 'Lunes a Viernes 8:00 a 16:00 hs',
            'logo_path' => $s->logo_path,
            'favicon_path' => $s->favicon_path,
            'facebook_url' => $s->facebook_url ?? '',
            'instagram_url' => $s->instagram_url ?? '',
            'youtube_url' => $s->youtube_url ?? '',
            'tiktok_url' => $s->tiktok_url ?? '',
            'institutional_text' => $s->institutional_text ?? '',
            'footer_text' => $s->footer_text ?? '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Configuracion')
                    ->tabs([
                        Tab::make('Datos de contacto')
                            ->schema([
                                Section::make('Identidad del sitio')
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Nombre del sindicato')
                                            ->required()
                                            ->maxLength(150),
                                    ]),
                                Section::make('Logo e imagen')
                                    ->columns(2)
                                    ->schema([
                                        FileUpload::make('logo_path')
                                            ->label('Logo del sindicato')
                                            ->image()
                                            ->imageEditor()
                                            ->disk('public')
                                            ->directory('site')
                                            ->visibility('public')
                                            ->imagePreviewHeight('100'),
                                        FileUpload::make('favicon_path')
                                            ->label('Favicon')
                                            ->image()
                                            ->disk('public')
                                            ->directory('site')
                                            ->visibility('public')
                                            ->imagePreviewHeight('60'),
                                    ]),
                                Section::make('Datos de contacto institucional')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('address')->label('Direccion')->maxLength(255),
                                        TextInput::make('phone')->label('Telefono principal')->maxLength(50),
                                        TextInput::make('whatsapp')->label('Numero de WhatsApp')->maxLength(30),
                                        TextInput::make('email')->label('Correo electronico')->email()->maxLength(150),
                                        Textarea::make('schedule')->label('Horarios de atencion')->rows(2)->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Redes sociales')
                            ->schema([
                                Section::make('Cuentas en redes sociales')
                                    ->schema([
                                        TextInput::make('facebook_url')->label('Facebook')->url()->maxLength(255),
                                        TextInput::make('instagram_url')->label('Instagram')->url()->maxLength(255),
                                        TextInput::make('youtube_url')->label('YouTube')->url()->maxLength(255),
                                        TextInput::make('tiktok_url')->label('TikTok')->url()->maxLength(255),
                                    ]),
                            ]),
                        Tab::make('Textos del sitio')
                            ->schema([
                                Section::make('Textos que aparecen en el sitio y el footer')
                                    ->schema([
                                        Textarea::make('institutional_text')->label('Texto institucional')->rows(4)->maxLength(500),
                                        Textarea::make('footer_text')->label('Texto del pie de pagina')->rows(3)->maxLength(400),
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
            Action::make('save')
                ->label('Guardar cambios')
                ->action('save')
                ->icon('heroicon-o-check')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $update = [
            'site_name' => $data['site_name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'whatsapp' => $data['whatsapp'],
            'email' => $data['email'],
            'schedule' => $data['schedule'],
            'facebook_url' => $data['facebook_url'],
            'instagram_url' => $data['instagram_url'],
            'youtube_url' => $data['youtube_url'],
            'tiktok_url' => $data['tiktok_url'],
            'institutional_text' => $data['institutional_text'],
            'footer_text' => $data['footer_text'],
        ];

        if ($data['logo_path'] !== null) {
            $update['logo_path'] = $data['logo_path'];
        }

        if ($data['favicon_path'] !== null) {
            $update['favicon_path'] = $data['favicon_path'];
        }

        SiteSetting::current()->update($update);

        Notification::make()
            ->title('Datos del sitio actualizados')
            ->success()
            ->send();
    }
}
