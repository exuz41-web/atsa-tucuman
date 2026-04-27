<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delegado extends Model
{
    protected $table = 'delegados';

    protected $fillable = [
        'nombre',
        'filial_id',
        'sector',
        'telefono',
        'email',
        'foto',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function filial(): BelongsTo
    {
        return $this->belongsTo(Filial::class);
    }
}
