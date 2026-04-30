<?php

namespace App\Console\Commands;

use App\Models\Prestador;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerarAccesosPrestadores extends Command
{
    protected $signature = 'prestadores:generar-accesos
        {--reset-passwords : Regenera contraseñas aunque ya existan}
        {--incluir-inactivos : Incluye prestadores inactivos}
        {--csv= : Guarda los accesos generados en un CSV}';

    protected $description = 'Genera usuario, contraseña y acceso al portal para prestadores.';

    public function handle(): int
    {
        $query = Prestador::query()
            ->orderBy('nombre')
            ->when(! $this->option('incluir-inactivos'), fn ($query) => $query->where('activo', true));

        $resetPasswords = (bool) $this->option('reset-passwords');
        $rows = $query
            ->get()
            ->map(function (Prestador $prestador) use ($resetPasswords): array {
                $credentials = $prestador->asegurarAccesoPortal(resetPassword: $resetPasswords);

                return [
                    'prestador' => $credentials['nombre'],
                    'usuario' => $credentials['usuario'],
                    'password' => $credentials['password'] ?: '(sin cambios)',
                    'login' => $credentials['login'],
                    'portal' => $credentials['portal'],
                ];
            });

        if ($rows->isEmpty()) {
            $this->warn('No hay prestadores para procesar.');

            return self::SUCCESS;
        }

        $this->table(['Prestador', 'Usuario', 'Contraseña', 'Login', 'Portal'], $rows->all());

        if ($csvPath = $this->option('csv')) {
            $path = base_path((string) $csvPath);
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $this->toCsv($rows->all()));
            $this->info('CSV generado: '.$path);
        }

        return self::SUCCESS;
    }

    private function toCsv(array $rows): string
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['prestador', 'usuario', 'password', 'login', 'portal']);

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return (string) $csv;
    }
}
