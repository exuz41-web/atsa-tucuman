<?php

namespace App\Services\Cent;

use App\Models\CentCuota;
use App\Models\CentRecibo;

class EmitirReciboCuota
{
    public function ejecutar(CentCuota $cuota, ?int $emitidoPor = null): CentRecibo
    {
        $cuota->loadMissing('alumno');

        return CentRecibo::firstOrCreate(
            ['cent_cuota_id' => $cuota->id],
            [
                'alumno_id' => $cuota->alumno_id,
                'monto' => $cuota->monto_final,
                'concepto' => $cuota->concepto,
                'periodo' => $cuota->periodo,
                'emitido_por' => $emitidoPor,
            ]
        );
    }
}
