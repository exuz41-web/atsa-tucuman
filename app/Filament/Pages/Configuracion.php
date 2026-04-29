<?php

namespace App\Filament\Pages;

use App\Helpers\LogActividad;
use App\Models\Configuracion as ConfiguracionModel;
use App\Models\SiteSetting;
use App\Support\BackupSupport;
use App\Support\MailSettings;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Arr;

class Configuracion extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Configuración y seguridad';

    protected static ?string $navigationLabel = 'Configuración general';

    protected static ?int $navigationSort = 10;

    protected static ?string $title = 'Configuración general';

    protected static string $view = 'filament.pages.configuracion';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasPagePermission(static::class, 'admin') ?? false;
    }

    public function mount(): void
    {
        $siteSettings = SiteSetting::current();

        $this->form->fill([
            'site_name' => $siteSettings->site_name,
            'logo_path' => $siteSettings->logo_path,
            'favicon_path' => $siteSettings->favicon_path,
            'institutional_text' => $siteSettings->institutional_text,
            'footer_text' => $siteSettings->footer_text,
            'telefono_principal' => ConfiguracionModel::get('telefono_principal', $siteSettings->phone),
            'telefono_cent' => ConfiguracionModel::get('telefono_cent'),
            'direccion' => ConfiguracionModel::get('direccion', $siteSettings->address),
            'email_contacto' => ConfiguracionModel::get('email_contacto', $siteSettings->email),
            'whatsapp' => ConfiguracionModel::get('whatsapp', $siteSettings->whatsapp),
            'horarios' => ConfiguracionModel::get('horarios', $siteSettings->schedule),
            'facebook' => ConfiguracionModel::get('facebook', $siteSettings->facebook_url),
            'instagram' => ConfiguracionModel::get('instagram', $siteSettings->instagram_url),
            'youtube' => ConfiguracionModel::get('youtube', $siteSettings->youtube_url),
            'tiktok' => ConfiguracionModel::get('tiktok', $siteSettings->tiktok_url),
            'twitter' => ConfiguracionModel::get('twitter'),
            'nombre_sitio' => ConfiguracionModel::get('nombre_sitio', $siteSettings->site_name),
            'descripcion_sitio' => ConfiguracionModel::get('descripcion_sitio'),
            'secretario_general' => ConfiguracionModel::get('secretario_general'),
            'anio_fundacion' => ConfiguracionModel::get('anio_fundacion'),
            'google_analytics_id' => ConfiguracionModel::get('google_analytics_id', $siteSettings->google_analytics_id),
            'public_site_enabled' => ConfiguracionModel::get('public_site_enabled', '1') === '1',
            'maintenance_message' => ConfiguracionModel::get('maintenance_message'),
            'backup_enabled' => ConfiguracionModel::get('backup_enabled', config('backup.enabled', true) ? '1' : '0') === '1',
            'backup_schedule' => ConfiguracionModel::get('backup_schedule', config('backup.schedule', '02:30')),
            'backup_keep_days' => ConfiguracionModel::get('backup_keep_days', (string) config('backup.keep_days', 14)),
            'smtp_enabled' => ConfiguracionModel::get('smtp_enabled', '0') === '1',
            'smtp_host' => ConfiguracionModel::get('smtp_host'),
            'smtp_port' => ConfiguracionModel::get('smtp_port', '587'),
            'smtp_username' => ConfiguracionModel::get('smtp_username'),
            'smtp_password' => MailSettings::decryptPassword(ConfiguracionModel::get('smtp_password')),
            'smtp_encryption' => ConfiguracionModel::get('smtp_encryption', 'tls'),
            'smtp_from_address' => ConfiguracionModel::get('smtp_from_address', $siteSettings->mail_from_address),
            'smtp_from_name' => ConfiguracionModel::get('smtp_from_name', $siteSettings->mail_from_name ?: 'ATSA Tucumán'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identidad del sitio')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('Nombre público')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('logo_path')
                            ->label('Logo principal')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->imageEditor(),
                        Forms\Components\FileUpload::make('favicon_path')
                            ->label('Ícono / favicon')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->imageEditor(),
                        Forms\Components\Textarea::make('institutional_text')
                            ->label('Texto institucional público')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('footer_text')
                            ->label('Texto de pie de página')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Datos de contacto')
                    ->schema([
                        Forms\Components\TextInput::make('telefono_principal')->label('Teléfono principal'),
                        Forms\Components\TextInput::make('telefono_cent')->label('Telefono CENT N°74'),
                        Forms\Components\TextInput::make('direccion')->label('Dirección'),
                        Forms\Components\TextInput::make('email_contacto')->label('Email')->email(),
                        Forms\Components\TextInput::make('whatsapp')->label('WhatsApp'),
                        Forms\Components\TextInput::make('horarios')->label('Horarios'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Redes sociales')
                    ->schema([
                        Forms\Components\TextInput::make('facebook')->label('Facebook')->url(),
                        Forms\Components\TextInput::make('instagram')->label('Instagram')->url(),
                        Forms\Components\TextInput::make('youtube')->label('YouTube')->url(),
                        Forms\Components\TextInput::make('tiktok')->label('TikTok')->url(),
                        Forms\Components\TextInput::make('twitter')->label('Twitter / X')->url(),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Información del sindicato')
                    ->schema([
                        Forms\Components\TextInput::make('nombre_sitio')->label('Nombre del sitio'),
                        Forms\Components\Textarea::make('descripcion_sitio')->label('Descripción')->rows(3),
                        Forms\Components\TextInput::make('secretario_general')->label('Secretario General'),
                        Forms\Components\TextInput::make('anio_fundacion')->label('Año de fundación')->numeric(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Web pública y analítica')
                    ->schema([
                        Forms\Components\Toggle::make('public_site_enabled')
                            ->label('Web pública activa')
                            ->helperText('Permite dejar registrado si la web pública está operativa o en pausa.'),
                        Forms\Components\TextInput::make('google_analytics_id')
                            ->label('Google Analytics ID')
                            ->placeholder('G-XXXXXXXXXX')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('maintenance_message')
                            ->label('Mensaje de mantenimiento')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Backups y recuperación')
                    ->description('El backup manual se genera desde esta pantalla. Estos valores también los usa el comando automático cuando está activo.')
                    ->schema([
                        Forms\Components\Toggle::make('backup_enabled')
                            ->label('Backups automáticos activos'),
                        Forms\Components\TextInput::make('backup_schedule')
                            ->label('Horario programado')
                            ->placeholder('02:30')
                            ->helperText('Formato 24 horas. Si se cambia, conviene reiniciar el scheduler/cron.'),
                        Forms\Components\TextInput::make('backup_keep_days')
                            ->label('Días de retención')
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Los backups más viejos se eliminan al correr el comando automático.'),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Correo SMTP')
                    ->description('Estos datos quedan preparados para cuando la comisión apruebe el servicio de correo. Si está desactivado, el sistema seguirá usando la configuración actual del proyecto.')
                    ->schema([
                        Forms\Components\Toggle::make('smtp_enabled')
                            ->label('Usar SMTP configurado desde el admin')
                            ->helperText('Activar solo cuando ATSA confirme proveedor, usuario y contraseña.'),
                        Forms\Components\TextInput::make('smtp_host')
                            ->label('Servidor SMTP')
                            ->placeholder('smtp.gmail.com'),
                        Forms\Components\TextInput::make('smtp_port')
                            ->label('Puerto')
                            ->numeric()
                            ->placeholder('587'),
                        Forms\Components\Select::make('smtp_encryption')
                            ->label('Seguridad')
                            ->options([
                                'tls' => 'TLS',
                                'ssl' => 'SSL',
                                '' => 'Sin cifrado',
                            ])
                            ->native(false),
                        Forms\Components\TextInput::make('smtp_username')
                            ->label('Usuario SMTP'),
                        Forms\Components\TextInput::make('smtp_password')
                            ->label('Contraseña SMTP')
                            ->password()
                            ->revealable()
                            ->helperText('Se guarda cifrada en la base de datos.'),
                        Forms\Components\TextInput::make('smtp_from_address')
                            ->label('Email remitente')
                            ->email()
                            ->placeholder('no-reply@atsatucuman.org'),
                        Forms\Components\TextInput::make('smtp_from_name')
                            ->label('Nombre remitente')
                            ->placeholder('ATSA Tucumán'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        $actions = [
            Action::make('guardar')
                ->label('Guardar configuración')
                ->color('primary')
                ->action('save'),
        ];

        if ($this->canManageBackups()) {
            $actions[] = Action::make('backup')
                ->label('Generar backup')
                ->icon('heroicon-o-archive-box-arrow-down')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Generar backup del sistema')
                ->modalDescription('Se generará un respaldo manual con base de datos y archivos almacenados en storage.')
                ->action('generateBackup');
        }

        return $actions;
    }

    public function save(): void
    {
        $data = $this->form->getState();

        SiteSetting::current()->update([
            'site_name' => $data['site_name'] ?: ($data['nombre_sitio'] ?? 'ATSA Tucumán'),
            'logo_path' => $this->uploadPath($data['logo_path'] ?? null),
            'favicon_path' => $this->uploadPath($data['favicon_path'] ?? null),
            'address' => $data['direccion'] ?? null,
            'phone' => $data['telefono_principal'] ?? null,
            'whatsapp' => $data['whatsapp'] ?? null,
            'email' => $data['email_contacto'] ?? null,
            'schedule' => $data['horarios'] ?? null,
            'facebook_url' => $data['facebook'] ?? null,
            'instagram_url' => $data['instagram'] ?? null,
            'youtube_url' => $data['youtube'] ?? null,
            'tiktok_url' => $data['tiktok'] ?? null,
            'institutional_text' => $data['institutional_text'] ?? null,
            'footer_text' => $data['footer_text'] ?? null,
            'google_analytics_id' => $data['google_analytics_id'] ?? null,
            'mail_from_address' => $data['smtp_from_address'] ?? null,
            'mail_from_name' => $data['smtp_from_name'] ?? null,
        ]);

        $meta = [
            'site_name' => ['sitio', 'texto', 'Nombre público del sitio'],
            'logo_path' => ['sitio', 'texto', 'Logo principal'],
            'favicon_path' => ['sitio', 'texto', 'Icono / favicon'],
            'institutional_text' => ['sitio', 'texto', 'Texto institucional público'],
            'footer_text' => ['sitio', 'texto', 'Texto de pie de página'],
            'telefono_principal' => ['contacto', 'telefono', 'Teléfono principal'],
            'telefono_cent' => ['contacto', 'telefono', 'Telefono CENT N°74'],
            'direccion' => ['contacto', 'texto', 'Dirección institucional'],
            'email_contacto' => ['contacto', 'email', 'Email de contacto'],
            'whatsapp' => ['contacto', 'telefono', 'WhatsApp institucional'],
            'horarios' => ['contacto', 'texto', 'Horarios de atención'],
            'facebook' => ['redes_sociales', 'url', 'Facebook oficial'],
            'instagram' => ['redes_sociales', 'url', 'Instagram oficial'],
            'youtube' => ['redes_sociales', 'url', 'YouTube oficial'],
            'tiktok' => ['redes_sociales', 'url', 'TikTok oficial'],
            'twitter' => ['redes_sociales', 'url', 'Twitter / X oficial'],
            'nombre_sitio' => ['sitio', 'texto', 'Nombre del sitio'],
            'descripcion_sitio' => ['sitio', 'texto', 'Descripción institucional'],
            'secretario_general' => ['sitio', 'texto', 'Secretario General'],
            'anio_fundacion' => ['sitio', 'numero', 'Año de fundación'],
            'google_analytics_id' => ['sitio', 'texto', 'Google Analytics ID'],
            'public_site_enabled' => ['sitio', 'numero', 'Web pública activa'],
            'maintenance_message' => ['sitio', 'texto', 'Mensaje de mantenimiento'],
            'backup_enabled' => ['backups', 'numero', 'Backups automáticos activos'],
            'backup_schedule' => ['backups', 'texto', 'Horario de backups automáticos'],
            'backup_keep_days' => ['backups', 'numero', 'Días de retención de backups'],
            'smtp_enabled' => ['correo', 'numero', 'SMTP habilitado desde admin'],
            'smtp_host' => ['correo', 'texto', 'Servidor SMTP'],
            'smtp_port' => ['correo', 'numero', 'Puerto SMTP'],
            'smtp_username' => ['correo', 'texto', 'Usuario SMTP'],
            'smtp_password' => ['correo', 'texto', 'Contraseña SMTP cifrada'],
            'smtp_encryption' => ['correo', 'texto', 'Cifrado SMTP'],
            'smtp_from_address' => ['correo', 'email', 'Email remitente SMTP'],
            'smtp_from_name' => ['correo', 'texto', 'Nombre remitente SMTP'],
        ];

        foreach ($meta as $clave => [$grupo, $tipo, $descripcion]) {
            $valor = $data[$clave] ?? '';

            if ($clave === 'smtp_enabled') {
                $valor = ! empty($data[$clave]) ? '1' : '0';
            }

            if (in_array($clave, ['public_site_enabled', 'backup_enabled'], true)) {
                $valor = ! empty($data[$clave]) ? '1' : '0';
            }

            if (in_array($clave, ['logo_path', 'favicon_path'], true)) {
                $valor = $this->uploadPath($data[$clave] ?? null) ?? '';
            }

            if ($clave === 'smtp_password') {
                $valor = MailSettings::encryptPassword($data[$clave] ?? null);
            }

            ConfiguracionModel::updateOrCreate(
                ['clave' => $clave],
                [
                    'valor' => $valor,
                    'tipo' => $tipo,
                    'descripcion' => $descripcion,
                    'grupo' => $grupo,
                ]
            );
        }

        LogActividad::registrar('actualizo configuracion', 'Configuración', null, 'Actualizó la configuración general del sitio');

        Notification::make()
            ->title('Configuración guardada')
            ->success()
            ->send();
    }

    public function generateBackup(): void
    {
        $filename = BackupSupport::create();

        LogActividad::registrar('genero backup', 'Backup', null, 'Generó el backup '.$filename);

        Notification::make()
            ->title('Backup generado')
            ->body('Se creó el archivo '.$filename.'.')
            ->success()
            ->actions([
                NotificationAction::make('download')
                    ->label('Descargar')
                    ->url(route('panel.backups.download', ['filename' => $filename]), shouldOpenInNewTab: true),
            ])
            ->send();
    }

    public function getBackups(): array
    {
        if (! $this->canManageBackups()) {
            return [];
        }

        return BackupSupport::all();
    }

    public function canManageBackups(): bool
    {
        return auth()->user()?->hasPermission('admin.backups.manage') ?? false;
    }

    private function uploadPath(mixed $value): ?string
    {
        if (is_array($value)) {
            $value = Arr::first($value);
        }

        return filled($value) ? (string) $value : null;
    }
}
