<?php

namespace App\Services\Cent;

use App\Models\Comision;
use App\Models\Inscripcion;
use App\Models\MatriculaCent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InscribirMatriculaAComisiones
{
    /**
     * @return Collection<int, Comision>
     */
    public function comisionesDisponibles(MatriculaCent $matricula): Collection
    {
        return Comision::query()
            ->with(['materia.carrera', 'sede', 'docente'])
            ->where('year_cycle', $matricula->ciclo_lectivo)
            ->where(function ($query) use ($matricula): void {
                $query->where('cent_sede_id', $matricula->cent_sede_id)
                    ->orWhereNull('cent_sede_id');
            })
            ->whereHas('materia', fn ($query) => $query->where('carrera_id', $matricula->carrera_id))
            ->orderBy('materia_id')
            ->get();
    }

    /**
     * @param array<int, int|string> $comisionIds
     */
    public function ejecutar(MatriculaCent $matricula, array $comisionIds, string $estado = 'aprobada'): int
    {
        $comisionIds = collect($comisionIds)->filter()->map(fn ($id) => (int) $id)->unique()->values();

        if ($comisionIds->isEmpty()) {
            throw ValidationException::withMessages([
                'comisiones' => 'Seleccioná al menos una comisión para inscribir al alumno.',
            ]);
        }

        $validas = $this->comisionesDisponibles($matricula)->pluck('id');

        $invalidas = $comisionIds->diff($validas);
        if ($invalidas->isNotEmpty()) {
            throw ValidationException::withMessages([
                'comisiones' => 'Una o más comisiones no corresponden a la carrera, sede o ciclo lectivo de esta matrícula.',
            ]);
        }

        return DB::transaction(function () use ($matricula, $comisionIds, $estado): int {
            $creadas = 0;

            foreach ($comisionIds as $comisionId) {
                $inscripcion = Inscripcion::firstOrNew([
                    'alumno_id' => $matricula->user_id,
                    'comision_id' => $comisionId,
                ]);

                if (! $inscripcion->exists) {
                    $creadas++;
                }

                $inscripcion->status = $estado;
                $inscripcion->save();
            }

            if ($matricula->estado === 'inscripto') {
                $matricula->update(['estado' => 'cursando']);
            }

            return $creadas;
        });
    }

    public function opcionesParaFormulario(MatriculaCent $matricula): array
    {
        return $this->comisionesDisponibles($matricula)
            ->mapWithKeys(function (Comision $comision): array {
                $label = $comision->materia->name;
                $label .= ' Â· '.$comision->materia->carrera->name;
                $label .= ' - '.($comision->sede->nombre ?? 'Sede general');
                $label .= ' - Docente: '.($comision->docente->name ?? 'A designar');

                if ($comision->schedule) {
                    $label .= ' Â· '.$comision->schedule;
                }

                return [$comision->id => $label];
            })
            ->all();
    }
}

