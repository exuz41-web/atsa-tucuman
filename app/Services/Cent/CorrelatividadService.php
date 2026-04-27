<?php

namespace App\Services\Cent;

use App\Models\CentEquivalencia;
use App\Models\Materia;
use App\Models\Nota;
use App\Models\User;

class CorrelatividadService
{
    public function puedeRendir(User $alumno, Materia $materia): bool
    {
        return $this->faltantes($alumno, $materia) === [];
    }

    public function faltantes(User $alumno, Materia $materia): array
    {
        $correlativas = collect($materia->correlatives ?? [])
            ->map(fn ($value) => is_array($value) ? ($value['id'] ?? $value['materia_id'] ?? null) : $value)
            ->filter(fn ($value) => is_numeric($value))
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values();

        if ($correlativas->isEmpty()) {
            return [];
        }

        $aprobadasPorNota = Nota::where('alumno_id', $alumno->id)
            ->where('status', 'aprobado')
            ->whereHas('comision', fn ($query) => $query->whereIn('materia_id', $correlativas))
            ->with('comision.materia')
            ->get()
            ->pluck('comision.materia_id')
            ->filter()
            ->map(fn ($id) => (int) $id);

        $aprobadasPorEquivalencia = CentEquivalencia::where('alumno_id', $alumno->id)
            ->where('estado', 'aprobada')
            ->whereIn('materia_id', $correlativas)
            ->pluck('materia_id')
            ->map(fn ($id) => (int) $id);

        $aprobadas = $aprobadasPorNota->merge($aprobadasPorEquivalencia)->unique();

        return $correlativas
            ->diff($aprobadas)
            ->values()
            ->all();
    }
}

