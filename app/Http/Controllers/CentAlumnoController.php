<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaCent;
use App\Models\AvisoCent;
use App\Models\CentClase;
use App\Models\CentCuota;
use App\Models\CentTrabajoPractico;
use App\Models\Descarga;
use App\Models\InscripcionMesaCent;
use App\Models\Nota;
use App\Models\Post;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CentAlumnoController extends Controller
{
    public function dashboard(): View
    {
        $alumno = auth()->user();
        $this->autorizarAlumno($alumno);

        $data = $this->datosAlumno($alumno);

        return view('cent74.alumno.dashboard', $data + [
            'alumno' => $alumno,
            'documentos' => Descarga::where('active', true)->latest()->limit(6)->get(),
            'novedades' => Post::whereNotNull('published_at')->where('category', 'formacion')->latest('published_at')->limit(3)->get(),
            'avisos' => $this->avisosParaUsuario($alumno, 'alumno'),
            'cuotasPendientes' => CentCuota::where('alumno_id', $alumno->id)
                ->whereIn('estado', ['pendiente', 'vencida'])
                ->orderBy('vencimiento')
                ->limit(5)
                ->get(),
            'clasesProximas' => CentClase::whereIn('comision_id', $data['inscripciones']->pluck('comision_id'))
                ->where('publicada', true)
                ->where('fecha_inicio', '>=', now()->subDay())
                ->orderBy('fecha_inicio')
                ->limit(5)
                ->get(),
            'trabajosPendientes' => CentTrabajoPractico::whereIn('comision_id', $data['inscripciones']->pluck('comision_id'))
                ->where('publicado', true)
                ->whereDoesntHave('entregas', fn ($query) => $query->where('alumno_id', $alumno->id))
                ->orderBy('fecha_entrega')
                ->limit(5)
                ->get(),
        ]);
    }

    public function carrera(): View
    {
        $alumno = auth()->user();
        $this->autorizarAlumno($alumno);

        return view('cent74.alumno.carrera', $this->datosAlumno($alumno) + [
            'alumno' => $alumno,
            'avisos' => $this->avisosParaUsuario($alumno, 'alumno'),
        ]);
    }

    public function notas(): View
    {
        $alumno = auth()->user();
        $this->autorizarAlumno($alumno);

        return view('cent74.alumno.notas', $this->datosAlumno($alumno) + [
            'alumno' => $alumno,
        ]);
    }

    public function ficha(): View
    {
        $alumno = auth()->user();
        $this->autorizarAlumno($alumno);

        return view('cent74.alumno.ficha', $this->datosAlumno($alumno) + [
            'alumno' => $alumno,
        ]);
    }

    public function constanciaPdf(): Response
    {
        $alumno = auth()->user();
        $this->autorizarAlumno($alumno);

        $matricula = $alumno->matriculasCent()->with(['carrera', 'sede'])->latest()->first();
        abort_unless($matricula, 404, 'No tenés una matrícula activa para emitir constancia.');

        return Pdf::loadView('cent74.pdf.constancia-alumno', [
            'alumno' => $alumno,
            'matricula' => $matricula,
        ])->download('constancia-cent-'.$alumno->id.'.pdf');
    }

    public function fichaPdf(): Response
    {
        $alumno = auth()->user();
        $this->autorizarAlumno($alumno);

        $matricula = $alumno->matriculasCent()->with(['carrera', 'sede'])->latest()->first();
        abort_unless($matricula, 404, 'No tenés una matrícula activa para emitir ficha académica.');

        $notas = Nota::where('alumno_id', $alumno->id)
            ->with(['comision.materia.carrera', 'cargadaPor'])
            ->latest()
            ->get();

        $inscripcionesMesa = InscripcionMesaCent::where('alumno_id', $alumno->id)
            ->with(['mesa.materia.carrera', 'mesa.sede', 'mesa.docente', 'cargadoPor'])
            ->latest()
            ->get();

        return Pdf::loadView('cent74.pdf.ficha-academica-alumno', [
            'alumno' => $alumno,
            'matricula' => $matricula,
            'notas' => $notas,
            'inscripcionesMesa' => $inscripcionesMesa,
        ])->download('ficha-academica-cent-'.$alumno->id.'.pdf');
    }

    private function autorizarAlumno($alumno): void
    {
        $centRole = $alumno->cent_role ?: $alumno->role;
        abort_unless(in_array($centRole, ['alumno', 'admin', 'directivo', 'coordinador'], true), 403);
    }

    private function datosAlumno($alumno): array
    {
        $matriculas = $alumno->matriculasCent()
            ->with(['carrera.materias', 'sede'])
            ->latest()
            ->get();

        $matriculaActiva = $matriculas->firstWhere('estado', 'activo') ?? $matriculas->first();

        $inscripciones = $alumno->inscripcionesAcademicas()
            ->with(['comision.materia.carrera', 'comision.sede', 'comision.docente'])
            ->latest()
            ->get();

        $notas = Nota::where('alumno_id', $alumno->id)
            ->with(['comision.materia.carrera', 'cargadaPor'])
            ->latest()
            ->get();

        $asistencias = AsistenciaCent::where('alumno_id', $alumno->id)
            ->with(['comision.materia.carrera'])
            ->latest('fecha')
            ->get();

        $inscripcionesMesa = InscripcionMesaCent::where('alumno_id', $alumno->id)
            ->with(['mesa.materia.carrera', 'mesa.sede', 'mesa.docente'])
            ->latest()
            ->get();

        return [
            'matriculas' => $matriculas,
            'matriculaActiva' => $matriculaActiva,
            'inscripciones' => $inscripciones,
            'notas' => $notas,
            'asistencias' => $asistencias,
            'inscripcionesMesa' => $inscripcionesMesa,
            'materiasPlan' => $matriculaActiva?->carrera?->materias?->sortBy([['year', 'asc'], ['semester', 'asc'], ['name', 'asc']]) ?? collect(),
        ];
    }

    private function avisosParaUsuario($user, string $rol)
    {
        $matriculas = $user->matriculasCent()->get(['carrera_id', 'cent_sede_id']);
        $carreraIds = $matriculas->pluck('carrera_id')->filter()->unique()->values();
        $sedeIds = $matriculas->pluck('cent_sede_id')->filter()->unique()->values();

        return AvisoCent::vigentes()
            ->whereIn('rol_destino', ['todos', $rol])
            ->where(fn ($query) => $query->whereNull('carrera_id')->orWhereIn('carrera_id', $carreraIds))
            ->where(fn ($query) => $query->whereNull('cent_sede_id')->orWhereIn('cent_sede_id', $sedeIds))
            ->with(['carrera', 'sede'])
            ->orderByDesc('destacado')
            ->latest()
            ->limit(5)
            ->get();
    }
}

