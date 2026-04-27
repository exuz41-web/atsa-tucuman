<?php

namespace App\Http\Controllers;

use App\Models\CentCuota;
use App\Models\Comision;
use App\Models\InscripcionMesaCent;
use App\Models\MatriculaCent;
use App\Models\MesaExamenCent;
use App\Models\PreinscripcionCent;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CentReporteController extends Controller
{
    public function alumnosCsv(): StreamedResponse
    {
        return $this->csv('alumnos-cent.csv', ['Legajo', 'Alumno', 'DNI', 'Carrera', 'Sede', 'Estado'], function ($out) {
            MatriculaCent::with(['alumno', 'carrera', 'sede'])
                ->when($this->sedeScopeId(), fn ($q, $sedeId) => $q->where('cent_sede_id', $sedeId))
                ->orderByDesc('ciclo_lectivo')
                ->each(fn ($m) => fputcsv($out, [
                    $m->legajo,
                    $m->alumno->name ?? '',
                    $m->alumno->dni ?? '',
                    $m->carrera->name ?? '',
                    $m->sede->nombre ?? '',
                    $m->estado,
                ], ';'));
        });
    }

    public function cuotasCsv(): StreamedResponse
    {
        return $this->csv('cuotas-cent.csv', ['Alumno', 'DNI', 'Concepto', 'Periodo', 'Monto', 'Final', 'Vencimiento', 'Estado'], function ($out) {
            CentCuota::with(['alumno', 'matricula.sede'])
                ->when($this->sedeScopeId(), fn ($q, $sedeId) => $q->whereHas('matricula', fn ($m) => $m->where('cent_sede_id', $sedeId)))
                ->orderByDesc('vencimiento')
                ->each(fn ($c) => fputcsv($out, [
                    $c->alumno->name ?? '',
                    $c->alumno->dni ?? '',
                    $c->concepto,
                    $c->periodo,
                    $c->monto,
                    $c->monto_final,
                    $c->vencimiento?->format('d/m/Y'),
                    $c->estado,
                ], ';'));
        });
    }

    public function preinscripcionesCsv(): StreamedResponse
    {
        return $this->csv('preinscripciones-cent.csv', ['Código', 'Nombre', 'DNI', 'Email', 'Teléfono', 'Carrera', 'Sede', 'Estado', 'Fecha'], function ($out) {
            PreinscripcionCent::with(['carrera', 'sede'])
                ->when($this->sedeScopeId(), fn ($q, $sedeId) => $q->where('cent_sede_id', $sedeId))
                ->latest()
                ->each(fn ($p) => fputcsv($out, [
                    $p->codigo,
                    $p->apellido_nombre,
                    $p->dni,
                    $p->email,
                    $p->telefono,
                    $p->carrera->name ?? '',
                    $p->sede->nombre ?? '',
                    $p->estado,
                    $p->created_at?->format('d/m/Y'),
                ], ';'));
        });
    }

    public function actasCsv(): StreamedResponse
    {
        return $this->csv('actas-cursado-cent.csv', ['Materia', 'Carrera', 'Sede', 'Docente', 'Ciclo', 'Estado', 'Alumnos'], function ($out) {
            Comision::with(['materia.carrera', 'sede', 'docente'])
                ->withCount('inscripciones')
                ->when($this->sedeScopeId(), fn ($q, $sedeId) => $q->where('cent_sede_id', $sedeId))
                ->orderByDesc('year_cycle')
                ->each(fn ($c) => fputcsv($out, [
                    $c->materia->name ?? '',
                    $c->materia->carrera->name ?? '',
                    $c->sede->nombre ?? '',
                    $c->docente->name ?? '',
                    $c->year_cycle,
                    $c->acta_estado ?? 'abierta',
                    $c->inscripciones_count,
                ], ';'));
        });
    }

    public function mesasCsv(): StreamedResponse
    {
        return $this->csv('mesas-examen-cent.csv', ['Materia', 'Carrera', 'Sede', 'Fecha', 'Turno', 'Estado', 'Inscriptos'], function ($out) {
            MesaExamenCent::with(['materia.carrera', 'sede'])
                ->withCount('inscripciones')
                ->when($this->sedeScopeId(), fn ($q, $sedeId) => $q->where('cent_sede_id', $sedeId))
                ->latest('fecha')
                ->each(fn ($mesa) => fputcsv($out, [
                    $mesa->materia->name ?? '',
                    $mesa->materia->carrera->name ?? '',
                    $mesa->sede->nombre ?? '',
                    $mesa->fecha?->format('d/m/Y'),
                    $mesa->turno,
                    $mesa->estado,
                    $mesa->inscripciones_count,
                ], ';'));
        });
    }

    public function finalesCsv(): StreamedResponse
    {
        return $this->csv('inscripciones-finales-cent.csv', ['Alumno', 'DNI', 'Materia', 'Sede', 'Fecha mesa', 'Estado', 'Nota'], function ($out) {
            InscripcionMesaCent::with(['alumno', 'mesa.materia', 'mesa.sede'])
                ->when($this->sedeScopeId(), fn ($q, $sedeId) => $q->whereHas('mesa', fn ($m) => $m->where('cent_sede_id', $sedeId)))
                ->latest()
                ->each(fn ($inscripcion) => fputcsv($out, [
                    $inscripcion->alumno->name ?? '',
                    $inscripcion->alumno->dni ?? '',
                    $inscripcion->mesa->materia->name ?? '',
                    $inscripcion->mesa->sede->nombre ?? '',
                    $inscripcion->mesa->fecha?->format('d/m/Y'),
                    $inscripcion->estado,
                    $inscripcion->nota,
                ], ';'));
        });
    }

    public function reportesPdf(): Response
    {
        $sedeScope = $this->sedeScopeId();

        $data = [
            'matriculas' => MatriculaCent::when($sedeScope, fn ($q) => $q->where('cent_sede_id', $sedeScope))->count(),
            'morosos' => CentCuota::whereIn('estado', ['pendiente', 'vencida'])
                ->when($sedeScope, fn ($q) => $q->whereHas('matricula', fn ($m) => $m->where('cent_sede_id', $sedeScope)))
                ->count(),
            'preinscripciones' => PreinscripcionCent::when($sedeScope, fn ($q) => $q->where('cent_sede_id', $sedeScope))->count(),
            'actasPendientes' => Comision::when($sedeScope, fn ($q) => $q->where('cent_sede_id', $sedeScope))->whereIn('acta_estado', ['abierta', 'cerrada'])->count(),
            'mesas' => MesaExamenCent::when($sedeScope, fn ($q) => $q->where('cent_sede_id', $sedeScope))->withCount('inscripciones')->latest('fecha')->limit(20)->get(),
            'cuotas' => CentCuota::with(['alumno', 'matricula.sede'])->when($sedeScope, fn ($q) => $q->whereHas('matricula', fn ($m) => $m->where('cent_sede_id', $sedeScope)))->latest('vencimiento')->limit(20)->get(),
        ];

        return Pdf::loadView('cent74.pdf.reporte-general', $data)
            ->setPaper('a4', 'landscape')
            ->download('reporte-cent-'.now()->format('Ymd').'.pdf');
    }

    private function csv(string $filename, array $headers, callable $callback): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $callback) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers, ';');
            $callback($out);
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function sedeScopeId(): ?int
    {
        $user = auth()->user();
        $role = $user->cent_role ?: $user->role;

        return $user->cent_sede_id && in_array($role, ['coordinador', 'directivo'], true)
            ? (int) $user->cent_sede_id
            : null;
    }
}

