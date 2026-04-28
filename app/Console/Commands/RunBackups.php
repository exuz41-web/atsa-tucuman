<?php

namespace App\Console\Commands;

use App\Helpers\LogActividad;
use App\Models\Configuracion;
use App\Support\BackupSupport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Throwable;

class RunBackups extends Command
{
    protected $signature = 'backups:run {--keep-days= : Días de retención para eliminar backups viejos}';

    protected $description = 'Genera un backup completo del sistema y limpia respaldos viejos';

    public function handle(): int
    {
        if (! $this->backupsEnabled()) {
            $this->warn('Los backups automáticos están desactivados por configuración.');

            return self::SUCCESS;
        }

        $keepDays = $this->option('keep-days');
        $keepDays = is_numeric($keepDays) ? max((int) $keepDays, 0) : $this->configuredKeepDays();

        $filename = BackupSupport::create();
        $deleted = BackupSupport::pruneOlderThanDays($keepDays);

        LogActividad::registrar(
            'genero backup automatico',
            'Backup',
            null,
            'Se generó '.$filename.' y se eliminaron '.$deleted.' backups viejos.'
        );

        $this->info('Backup generado: '.$filename);
        $this->info('Backups eliminados por retención: '.$deleted);

        return self::SUCCESS;
    }

    private function backupsEnabled(): bool
    {
        try {
            if (Schema::hasTable('configuraciones')) {
                $configured = Configuracion::get('backup_enabled');

                if ($configured !== null) {
                    return $configured === '1';
                }
            }
        } catch (Throwable) {
            //
        }

        return (bool) config('backup.enabled', true);
    }

    private function configuredKeepDays(): int
    {
        try {
            if (Schema::hasTable('configuraciones')) {
                $configured = Configuracion::get('backup_keep_days');

                if (is_numeric($configured)) {
                    return max((int) $configured, 0);
                }
            }
        } catch (Throwable) {
            //
        }

        return (int) config('backup.keep_days', 14);
    }
}
