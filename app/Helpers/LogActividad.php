<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogActividad
{
    public static function registrar(string $accion, string $modelo, mixed $modeloId = null, ?string $descripcion = null): void
    {
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'accion' => $accion,
                'modelo' => $modelo,
                'modelo_id' => $modeloId,
                'descripcion' => $descripcion,
                'ip' => request()?->ip(),
            ]);
        } catch (\Throwable) {
            // El log no debe bloquear una operación administrativa.
        }
    }
}
