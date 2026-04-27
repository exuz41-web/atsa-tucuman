<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentCuota extends Model
{
    protected $table = 'cent_cuotas';

    protected $fillable = [
        'matricula_cent_id',
        'alumno_id',
        'concepto',
        'periodo',
        'monto',
        'descuento_tipo',
        'descuento_porcentaje',
        'descuento_monto',
        'afiliado_descuento_id',
        'monto_final',
        'vencimiento',
        'estado',
        'comprobante',
        'pagado_at',
        'observaciones',
        'creado_por',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'descuento_porcentaje' => 'decimal:2',
            'descuento_monto' => 'decimal:2',
            'monto_final' => 'decimal:2',
            'vencimiento' => 'date',
            'pagado_at' => 'datetime',
        ];
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(MatriculaCent::class, 'matricula_cent_id');
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function afiliadoDescuento(): BelongsTo
    {
        return $this->belongsTo(User::class, 'afiliado_descuento_id');
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function recibo()
    {
        return $this->hasOne(CentRecibo::class, 'cent_cuota_id');
    }

    public function recalcularMontoFinal(): void
    {
        $descuentoPorcentaje = ((float) $this->descuento_porcentaje / 100) * (float) $this->monto;
        $this->monto_final = max(0, (float) $this->monto - $descuentoPorcentaje - (float) $this->descuento_monto);
    }

    protected static function booted(): void
    {
        static::saving(function (CentCuota $cuota): void {
            $cuota->recalcularMontoFinal();
            if ($cuota->estado === 'pagada' && ! $cuota->pagado_at) {
                $cuota->pagado_at = now();
            }
        });
    }
}
