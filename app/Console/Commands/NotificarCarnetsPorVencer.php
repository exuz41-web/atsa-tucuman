<?php

namespace App\Console\Commands;

use App\Helpers\LogActividad;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Console\Command;

class NotificarCarnetsPorVencer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carnets:notificar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registra alertas de carnets de afiliados que vencen en 30 dias';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $desde = now()->startOfDay();
        $hasta = now()->addDays(30)->endOfDay();

        $usuarios = User::query()
            ->where('carnet_activo', true)
            ->whereNotNull('numero_afiliado')
            ->whereBetween('carnet_vencimiento', [$desde, $hasta])
            ->get();

        foreach ($usuarios as $usuario) {
            ActivityLog::firstOrCreate(
                [
                    'accion' => 'carnet por vencer',
                    'modelo' => 'User',
                    'modelo_id' => $usuario->id,
                    'descripcion' => 'El carnet de '.$usuario->name.' vence el '.optional($usuario->carnet_vencimiento)->format('d/m/Y'),
                ],
                [
                    'user_id' => null,
                    'ip' => null,
                ]
            );
        }

        LogActividad::registrar('ejecuto aviso carnets', 'User', null, 'Carnets por vencer detectados: '.$usuarios->count());

        $this->info('Carnets por vencer detectados: '.$usuarios->count());

        return self::SUCCESS;
    }
}
