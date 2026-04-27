<?php

namespace App\Console\Commands;

use App\Helpers\LogActividad;
use App\Support\BackupSupport;
use Illuminate\Console\Command;

class RunBackups extends Command
{
    protected $signature = 'backups:run {--keep-days= : Días de retención para eliminar backups viejos}';

    protected $description = 'Genera un backup completo del sistema y limpia respaldos viejos';

    public function handle(): int
    {
        if (! config('backup.enabled', true)) {
            $this->warn('Los backups automáticos están desactivados por configuración.');

            return self::SUCCESS;
        }

        $keepDays = $this->option('keep-days');
        $keepDays = is_numeric($keepDays) ? max((int) $keepDays, 0) : (int) config('backup.keep_days', 14);

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
}
