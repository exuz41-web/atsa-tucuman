<?php

namespace App\Services\Cent;

use App\Models\CentNotificacion;
use App\Models\User;

class CentNotificar
{
    public static function usuario(User|int|null $user, string $titulo, string $mensaje, string $tipo = 'info', ?string $url = null): void
    {
        $userId = $user instanceof User ? $user->id : $user;

        if (! $userId) {
            return;
        }

        CentNotificacion::create([
            'user_id' => $userId,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'tipo' => $tipo,
            'url' => $url,
        ]);
    }

    public static function sede(?int $sedeId, string $titulo, string $mensaje, string $tipo = 'info', ?string $url = null): void
    {
        CentNotificacion::create([
            'cent_sede_id' => $sedeId,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'tipo' => $tipo,
            'url' => $url,
        ]);
    }
}
