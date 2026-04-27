<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentActivityLog extends Model
{
    protected $table = 'cent_activity_logs';

    protected $fillable = [
        'user_id',
        'accion',
        'modelo',
        'modelo_id',
        'descripcion',
        'ip',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function registrar(string $accion, ?Model $modelo = null, ?string $descripcion = null): void
    {
        static::create([
            'user_id' => auth()->id(),
            'accion' => $accion,
            'modelo' => $modelo ? class_basename($modelo) : null,
            'modelo_id' => $modelo?->getKey(),
            'descripcion' => $descripcion,
            'ip' => request()?->ip(),
        ]);
    }
}
