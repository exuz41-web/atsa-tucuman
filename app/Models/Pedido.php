<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'afiliado_id',
        'tipo',
        'descripcion',
        'estado',
        'archivo_dni',
        'archivo_recibo',
        'archivo_adicional',
        'observaciones',
    ];

    public function afiliado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'afiliado_id');
    }
}
