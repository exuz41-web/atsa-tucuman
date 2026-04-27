<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consulta extends Model
{
    protected $table = 'consultas';

    protected $fillable = [
        'afiliado_id',
        'tipo',
        'asunto',
        'mensaje',
        'fecha_solicitada',
        'estado',
        'respuesta',
    ];

    protected function casts(): array
    {
        return [
            'fecha_solicitada' => 'date',
        ];
    }

    public function afiliado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'afiliado_id');
    }
}
