<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentNotificacion extends Model
{
    protected $table = 'cent_notificaciones';

    protected $fillable = ['user_id', 'cent_sede_id', 'titulo', 'mensaje', 'tipo', 'url', 'leida_at'];

    protected function casts(): array
    {
        return ['leida_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(CentSede::class, 'cent_sede_id');
    }
}
