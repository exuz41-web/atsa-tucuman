<?php

namespace App\Http\Controllers;

use App\Models\CentEntregaTrabajo;
use App\Models\CentClase;
use App\Models\CentMaterial;
use App\Models\CentPermisoExamen;
use App\Models\CentTrabajoPractico;
use App\Models\Comision;
use App\Models\InscripcionMesaCent;
use App\Models\MesaExamenCent;
use App\Services\Cent\CentNotificar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CentClassroomController extends Controller
{
    public function aulaAlumno(): View
    {
        $alumno = auth()->user();
        $comisionIds = $alumno->inscripcionesAcademicas()
            ->whereIn('status', ['pendiente', 'aprobada', 'regular', 'inscripto'])
            ->pluck('comision_id');

        $comisiones = Comision::whereIn('id', $comisionIds)
            ->with(['materia.carrera', 'sede', 'docente'])
            ->get();

        $clases = \App\Models\CentClase::whereIn('comision_id', $comisionIds)
            ->where('publicada', true)
            ->with(['comision.materia'])
            ->latest('fecha_inicio')
            ->limit(12)
            ->get();

        $materiales = \App\Models\CentMaterial::whereIn('comision_id', $comisionIds)
            ->where('publicado', true)
            ->with(['comision.materia', 'clase'])
            ->latest()
            ->limit(12)
            ->get();

        $trabajos = CentTrabajoPractico::whereIn('comision_id', $comisionIds)
            ->where('publicado', true)
            ->with(['comision.materia', 'entregas' => fn ($query) => $query->where('alumno_id', $alumno->id)])
            ->latest('fecha_entrega')
            ->get();

        return view('cent74.alumno.aula', compact('alumno', 'comisiones', 'clases', 'materiales', 'trabajos'));
    }

    public function aulaDocente(): View
    {
        $docente = auth()->user();
        $role = $docente->cent_role ?: $docente->role;

        $comisiones = Comision::query()
            ->when(! in_array($role, ['admin', 'directivo', 'coordinador'], true), fn ($query) => $query->where('docente_id', $docente->id))
            ->with(['materia.carrera', 'sede', 'clases', 'materiales', 'trabajosPracticos.entregas'])
            ->latest()
            ->get();

        return view('cent74.docente.aula', compact('docente', 'comisiones'));
    }

    public function guardarClaseDocente(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'comision_id' => ['required', 'exists:comisiones,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'modalidad' => ['required', 'in:presencial,virtual,mixta'],
            'aula' => ['nullable', 'string', 'max:120'],
            'link_virtual' => ['nullable', 'url', 'max:255'],
        ]);

        $this->autorizarComisionDocente((int) $data['comision_id']);
        $data['creado_por'] = auth()->id();
        $data['publicada'] = true;

        $clase = CentClase::create($data);
        $clase->load('comision.sede');
        CentNotificar::sede($clase->comision?->cent_sede_id, 'Nueva clase publicada', 'Se publicó la clase '.$clase->titulo.'.', 'aula', route('cent.alumno.aula'));

        return back()->with('status', 'Clase publicada correctamente.');
    }

    public function guardarMaterialDocente(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'comision_id' => ['required', 'exists:comisiones,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'tipo' => ['required', 'in:apunte,video,link,presentacion,guia,otro'],
            'archivo' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png,zip', 'max:12288'],
            'url' => ['nullable', 'url', 'max:255'],
        ]);

        $this->autorizarComisionDocente((int) $data['comision_id']);

        if ($request->hasFile('archivo')) {
            $data['archivo'] = $request->file('archivo')->store('cent/materiales/'.auth()->id(), 'public');
        }

        $data['creado_por'] = auth()->id();
        $data['publicado'] = true;

        $material = CentMaterial::create($data);
        $material->load('comision.sede');
        CentNotificar::sede($material->comision?->cent_sede_id, 'Nuevo material de estudio', 'Se publicó material: '.$material->titulo.'.', 'aula', route('cent.alumno.aula'));

        return back()->with('status', 'Material publicado correctamente.');
    }

    public function guardarTrabajoDocente(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'comision_id' => ['required', 'exists:comisiones,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'consigna' => ['required', 'string', 'max:4000'],
            'fecha_entrega' => ['nullable', 'date'],
            'puntaje_maximo' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'archivo_consigna' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png,zip', 'max:12288'],
        ]);

        $this->autorizarComisionDocente((int) $data['comision_id']);

        if ($request->hasFile('archivo_consigna')) {
            $data['archivo_consigna'] = $request->file('archivo_consigna')->store('cent/trabajos/'.auth()->id(), 'public');
        }

        $data['fecha_publicacion'] = now();
        $data['creado_por'] = auth()->id();
        $data['acepta_entregas'] = true;
        $data['publicado'] = true;

        $trabajo = CentTrabajoPractico::create($data);
        $trabajo->load('comision.sede');
        CentNotificar::sede($trabajo->comision?->cent_sede_id, 'Nuevo trabajo práctico', 'Se publicó el trabajo '.$trabajo->titulo.'.', 'aula', route('cent.alumno.aula'));

        return back()->with('status', 'Trabajo práctico publicado correctamente.');
    }

    public function entregarTrabajo(Request $request, CentTrabajoPractico $trabajo): RedirectResponse
    {
        $alumno = auth()->user();

        abort_unless($alumno->inscripcionesAcademicas()->where('comision_id', $trabajo->comision_id)->exists(), 403);

        if (! $trabajo->acepta_entregas) {
            return back()->withErrors(['trabajo' => 'Este trabajo ya no acepta entregas.']);
        }

        $data = $request->validate([
            'comentario' => ['nullable', 'string', 'max:2000'],
            'archivo' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png,zip', 'max:8192'],
        ]);

        $data['archivo'] = $request->file('archivo')->store('cent/entregas/'.$alumno->id, 'public');
        $data['estado'] = 'entregado';
        $data['entregado_at'] = now();

        $entrega = CentEntregaTrabajo::updateOrCreate(
            ['trabajo_practico_id' => $trabajo->id, 'alumno_id' => $alumno->id],
            $data
        );
        $entrega->load('trabajo.comision');
        CentNotificar::usuario($entrega->trabajo?->comision?->docente_id, 'Nueva entrega recibida', $alumno->name.' entregó el trabajo '.$trabajo->titulo.'.', 'aula', route('cent.docente.aula'));

        return back()->with('status', 'Entrega registrada correctamente.');
    }

    public function permisosAlumno(): View
    {
        $alumno = auth()->user();
        $permisos = CentPermisoExamen::where('alumno_id', $alumno->id)
            ->with(['mesa.materia.carrera', 'mesa.sede', 'cuota'])
            ->latest()
            ->get();

        $inscripciones = InscripcionMesaCent::where('alumno_id', $alumno->id)
            ->with(['mesa.materia.carrera', 'mesa.sede'])
            ->latest()
            ->get();

        return view('cent74.alumno.permisos', compact('alumno', 'permisos', 'inscripciones'));
    }

    public function solicitarPermiso(MesaExamenCent $mesa): RedirectResponse
    {
        $alumno = auth()->user();

        abort_unless(
            InscripcionMesaCent::where('alumno_id', $alumno->id)->where('mesa_examen_cent_id', $mesa->id)->exists(),
            403
        );

        $permiso = CentPermisoExamen::firstOrCreate(
            ['alumno_id' => $alumno->id, 'mesa_examen_cent_id' => $mesa->id],
            [
                'codigo' => $this->codigoPermiso(),
                'estado' => 'pendiente_pago',
                'monto' => 0,
                'qr_token' => (string) Str::uuid(),
                'observaciones' => 'Solicitud generada por el alumno desde el portal.',
            ]
        );
        CentNotificar::usuario($alumno->id, 'Permiso solicitado', 'Tu permiso de examen quedó pendiente de habilitación administrativa.', 'permiso', route('cent.alumno.permisos'));

        return redirect()->route('cent.alumno.permisos')->with('status', 'Permiso solicitado. Administración debe habilitarlo cuando corresponda.');
    }

    public function permisoPdf(CentPermisoExamen $permiso): Response
    {
        abort_unless($permiso->alumno_id === auth()->id() || in_array(auth()->user()->cent_role ?: auth()->user()->role, ['admin', 'directivo', 'coordinador'], true), 403);

        $permiso->load(['alumno', 'mesa.materia.carrera', 'mesa.sede']);
        $url = route('cent.permisos.verificar', $permiso->qr_token);
        $qrCode = base64_encode(QrCode::format('png')->size(220)->errorCorrection('H')->generate($url));

        return Pdf::loadView('cent74.pdf.permiso-examen', compact('permiso', 'qrCode', 'url'))
            ->setPaper('a4')
            ->download('permiso-examen-'.$permiso->codigo.'.pdf');
    }

    public function verificarPermiso(string $token): View
    {
        $permiso = CentPermisoExamen::where('qr_token', $token)
            ->with(['alumno', 'mesa.materia.carrera', 'mesa.sede'])
            ->first();

        return view('cent74.verificar-permiso', compact('permiso'));
    }

    public function carnetAlumno(): View
    {
        $alumno = auth()->user();
        $matricula = $alumno->matriculasCent()->with(['carrera', 'sede'])->latest()->first();
        $alumno->cent_public_token ??= (string) Str::uuid();
        if ($alumno->isDirty('cent_public_token')) {
            $alumno->save();
        }

        $url = route('cent.carnet.verificar', $alumno->cent_public_token);
        $qrCode = base64_encode(QrCode::format('png')->size(220)->errorCorrection('H')->generate($url));

        return view('cent74.alumno.carnet', compact('alumno', 'matricula', 'qrCode', 'url'));
    }

    public function carnetPdf(): Response
    {
        $alumno = auth()->user();
        $matricula = $alumno->matriculasCent()->with(['carrera', 'sede'])->latest()->first();
        $alumno->cent_public_token ??= (string) Str::uuid();
        if ($alumno->isDirty('cent_public_token')) {
            $alumno->save();
        }

        $url = route('cent.carnet.verificar', $alumno->cent_public_token);
        $qrCode = base64_encode(QrCode::format('png')->size(260)->errorCorrection('H')->generate($url));

        return Pdf::loadView('cent74.pdf.carnet-alumno', compact('alumno', 'matricula', 'qrCode', 'url'))
            ->setPaper([0, 0, 255, 153], 'landscape')
            ->download('carnet-estudiante-cent-'.$alumno->id.'.pdf');
    }

    public function verificarCarnet(string $token): View
    {
        $alumno = \App\Models\User::where('cent_public_token', $token)
            ->where(fn ($query) => $query->where('cent_role', 'alumno')->orWhere('role', 'alumno'))
            ->with(['matriculasCent.carrera', 'matriculasCent.sede'])
            ->first();

        return view('cent74.verificar-carnet', compact('alumno'));
    }

    private function codigoPermiso(): string
    {
        do {
            $codigo = 'PEX-'.now()->format('Y').'-'.strtoupper(Str::random(6));
        } while (CentPermisoExamen::where('codigo', $codigo)->exists());

        return $codigo;
    }

    private function autorizarComisionDocente(int $comisionId): void
    {
        $user = auth()->user();
        $role = $user->cent_role ?: $user->role;

        abort_unless(
            in_array($role, ['admin', 'directivo', 'coordinador'], true)
            || Comision::whereKey($comisionId)->where('docente_id', $user->id)->exists(),
            403
        );
    }
}
