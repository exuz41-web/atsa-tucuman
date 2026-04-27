<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CentPermisoExamen extends Model
{
    protected $table = 'cent_permisos_examen';

    protected $fillable = [
        'codigo',
        'alumno_id',
        'mesa_examen_cent_id',
        'cent_cuota_id',
        'estado',
        'monto',
        'qr_token',
        'habilitado_at',
        'usado_at',
        'habilitado_por',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'habilitado_at' => 'datetime',
            'usado_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $permiso) {
            $permiso->codigo ??= 'PEX-'.now()->format('Y').'-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
            $permiso->qr_token ??= (string) Str::uuid();
        });
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(MesaExamenCent::class, 'mesa_examen_cent_id');
    }

    public function cuota(): BelongsTo
    {
        return $this->belongsTo(CentCuota::class, 'cent_cuota_id');
    }

    public function habilitador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'habilitado_por');
    }
}

