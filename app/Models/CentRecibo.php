<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CentRecibo extends Model
{
    protected $table = 'cent_recibos';

    protected $fillable = [
        'numero',
        'cent_cuota_id',
        'alumno_id',
        'monto',
        'concepto',
        'periodo',
        'qr_token',
        'emitido_at',
        'emitido_por',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'emitido_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $recibo) {
            $recibo->numero ??= 'REC-'.now()->format('Y').'-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
            $recibo->qr_token ??= (string) Str::uuid();
            $recibo->emitido_at ??= now();
        });
    }

    public function cuota(): BelongsTo
    {
        return $this->belongsTo(CentCuota::class, 'cent_cuota_id');
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }
}

