<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ExpedienteMovimiento extends Model
{
    protected $table = 'expediente_movimientos';

    protected $fillable = [
        'expediente_type',
        'expediente_id',
        'user_id',
        'secretaria_origen_id',
        'secretaria_destino_id',
        'estado_anterior',
        'estado_nuevo',
        'observacion_interna',
        'observacion_afiliado',
    ];

    public function expediente(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function secretariaOrigen(): BelongsTo
    {
        return $this->belongsTo(Secretaria::class, 'secretaria_origen_id');
    }

    public function secretariaDestino(): BelongsTo
    {
        return $this->belongsTo(Secretaria::class, 'secretaria_destino_id');
    }
}
