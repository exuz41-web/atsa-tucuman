<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CentConfiguracion extends Model
{
    protected $table = 'cent_configuraciones';

    protected $fillable = ['clave', 'valor', 'tipo', 'grupo', 'descripcion'];

    public static function get(string $clave, mixed $default = null): mixed
    {
        return static::where('clave', $clave)->value('valor') ?? $default;
    }

    public static function set(string $clave, mixed $valor, string $grupo = 'general', string $tipo = 'texto', ?string $descripcion = null): self
    {
        return static::updateOrCreate(
            ['clave' => $clave],
            [
                'valor' => is_bool($valor) ? ($valor ? '1' : '0') : (string) $valor,
                'grupo' => $grupo,
                'tipo' => $tipo,
                'descripcion' => $descripcion,
            ]
        );
    }
}
