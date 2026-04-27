<?php

namespace App\Filament\Pages;

use App\Helpers\LogActividad;
use App\Models\Configuracion as ConfiguracionModel;
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

class Configuracion extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Configuración';

    protected static ?string $navigationLabel = 'Configuración general';

    protected static ?string $title = 'Configuración general';

    protected static string $view = 'filament.pages.configuracion';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasPagePermission(static::class, 'admin') ?? false;
    }

    public function mount(): void
    {
        $this->form->fill([
            'telefono_principal' => ConfiguracionModel::get('telefono_principal'),
            'telefono_cent' => ConfiguracionModel::get('telefono_cent'),
            'direccion' => ConfiguracionModel::get('direccion'),
            'email_contacto' => ConfiguracionModel::get('email_contacto'),
            'whatsapp' => ConfiguracionModel::get('whatsapp'),
            'horarios' => ConfiguracionModel::get('horarios'),
            'facebook' => ConfiguracionModel::get('facebook'),
            'instagram' => ConfiguracionModel::get('instagram'),
            'twitter' => ConfiguracionModel::get('twitter'),
            'nombre_sitio' => ConfiguracionModel::get('nombre_sitio'),
            'descripcion_sitio' => ConfiguracionModel::get('descripcion_sitio'),
            'secretario_general' => ConfiguracionModel::get('secretario_general'),
            'anio_fundacion' => ConfiguracionModel::get('anio_fundacion'),
            'smtp_enabled' => ConfiguracionModel::get('smtp_enabled', '0') === '1',
            'smtp_host' => ConfiguracionModel::get('smtp_host'),
            'smtp_port' => ConfiguracionModel::get('smtp_port', '587'),
            'smtp_username' => ConfiguracionModel::get('smtp_username'),
            'smtp_password' => MailSettings::decryptPassword(ConfiguracionModel::get('smtp_password')),
            'smtp_encryption' => ConfiguracionModel::get('smtp_encryption', 'tls'),
            'smtp_from_address' => ConfiguracionModel::get('smtp_from_address'),
            'smtp_from_name' => ConfiguracionModel::get('smtp_from_name', 'ATSA Tucumán'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                        Forms\Components\TextInput::make('facebook')->url(),
                        Forms\Components\TextInput::make('instagram')->url(),
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

        $meta = [
            'telefono_principal' => ['contacto', 'telefono', 'Teléfono principal'],
            'telefono_cent' => ['contacto', 'telefono', 'Telefono CENT N°74'],
            'direccion' => ['contacto', 'texto', 'Dirección institucional'],
            'email_contacto' => ['contacto', 'email', 'Email de contacto'],
            'whatsapp' => ['contacto', 'telefono', 'WhatsApp institucional'],
            'horarios' => ['contacto', 'texto', 'Horarios de atención'],
            'facebook' => ['redes_sociales', 'url', 'Facebook oficial'],
            'instagram' => ['redes_sociales', 'url', 'Instagram oficial'],
            'twitter' => ['redes_sociales', 'url', 'Twitter / X oficial'],
            'nombre_sitio' => ['sitio', 'texto', 'Nombre del sitio'],
            'descripcion_sitio' => ['sitio', 'texto', 'Descripción institucional'],
            'secretario_general' => ['sitio', 'texto', 'Secretario General'],
            'anio_fundacion' => ['sitio', 'numero', 'Año de fundación'],
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
}
