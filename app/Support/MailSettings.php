<?php

namespace App\Support;

use App\Models\Configuracion;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Throwable;

class MailSettings
{
    public static function apply(): void
    {
        try {
            if (! Schema::hasTable('configuraciones')) {
                return;
            }

            $enabled = Configuracion::get('smtp_enabled', '0') === '1';

            if (! $enabled) {
                return;
            }

            $host = Configuracion::get('smtp_host');
            $port = Configuracion::get('smtp_port');
            $username = Configuracion::get('smtp_username');
            $password = self::decryptPassword(Configuracion::get('smtp_password'));
            $encryption = Configuracion::get('smtp_encryption');
            $fromAddress = Configuracion::get('smtp_from_address');
            $fromName = Configuracion::get('smtp_from_name', 'ATSA Tucumán');

            if (! $host || ! $port || ! $fromAddress) {
                return;
            }

            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $host,
                'mail.mailers.smtp.port' => (int) $port,
                'mail.mailers.smtp.encryption' => $encryption ?: null,
                'mail.mailers.smtp.username' => $username ?: null,
                'mail.mailers.smtp.password' => $password ?: null,
                'mail.from.address' => $fromAddress,
                'mail.from.name' => $fromName ?: 'ATSA Tucumán',
            ]);
        } catch (Throwable) {
            // During migrations or early boot the database may not be available.
        }
    }

    public static function encryptPassword(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return Crypt::encryptString($value);
    }

    public static function decryptPassword(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (Throwable) {
            return $value;
        }
    }
}
