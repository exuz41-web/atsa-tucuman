<?php

namespace App\Http\Controllers;

use App\Models\AvisoCent;
use App\Models\Carrera;
use App\Models\CentDescarga;
use App\Models\CentEvento;
use App\Models\CentHorario;
use App\Models\CentSede;
use App\Models\PreinscripcionCent;
use App\Models\SiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CentController extends Controller
{
    public function index(): View
    {
        return view('cent74.index', [
            'carreras'       => Carrera::where('active', true)->withCount('materias')->orderBy('name')->get(),
            'sedes'          => CentSede::where('activa', true)->orderBy('orden')->orderBy('nombre')->get(),
            'ultimasNoticias'=> AvisoCent::vigentes()->publicos()
                                    ->with(['carrera', 'sede'])
                                    ->orderBy('destacado', 'desc')
                                    ->latest()
                                    ->take(3)
                                    ->get(),
            'proximasMesas'  => CentEvento::where('activo', true)
                                    ->where('publico', true)
                                    ->whereIn('tipo', ['mesa', 'inscripcion'])
                                    ->where('fecha_inicio', '>=', now()->startOfDay())
                                    ->with(['sede', 'carrera'])
                                    ->orderBy('fecha_inicio')
                                    ->take(4)
                                    ->get(),
        ]);
    }

    public function carreras(): View
    {
        return view('cent74.carreras', [
            'carreras'   => Carrera::where('active', true)->withCount('materias')->with('materias')->orderBy('name')->get(),
            'sedesCount' => CentSede::where('activa', true)->count(),
        ]);
    }

    public function carrera(Carrera $carrera): View
    {
        abort_unless($carrera->active, 404);

        // Sedes desde la relación BD; si aún no hay datos en pivot, vacío collection
        $sedesDeCarrera = $carrera->centSedes()->where('activa', true)->orderBy('orden')->get();

        return view('cent74.carrera', [
            'carrera'        => $carrera->load(['materias' => fn ($q) => $q->orderBy('year')->orderBy('semester')->orderBy('id')]),
            'sedes'          => CentSede::where('activa', true)->orderBy('orden')->orderBy('nombre')->get(),
            'sedesDeCarrera' => $sedesDeCarrera,
        ]);
    }

    public function sedes(): View
    {
        return view('cent74.sedes', [
            'sedes' => CentSede::where('activa', true)->with('carreras')->orderBy('orden')->orderBy('nombre')->get(),
        ]);
    }

    public function requisitos(): View
    {
        return view('cent74.requisitos', [
            'carreras' => Carrera::where('active', true)->with('centSedes')->orderBy('name')->get(),
        ]);
    }

    public function faq(): View
    {
        return view('cent74.faq');
    }

    public function novedades(): View
    {
        $novedades = AvisoCent::vigentes()
            ->publicos()
            ->with(['carrera', 'sede'])
            ->orderBy('destacado', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $destacadas = AvisoCent::vigentes()
            ->publicos()
            ->where('destacado', true)
            ->with(['carrera', 'sede'])
            ->latest()
            ->take(3)
            ->get();

        return view('cent74.novedades', compact('novedades', 'destacadas'));
    }

    public function avisoShow(AvisoCent $aviso): View
    {
        abort_unless($aviso->activo && $aviso->publico, 404);

        $relacionados = AvisoCent::vigentes()
            ->publicos()
            ->where('id', '!=', $aviso->id)
            ->where(fn ($q) => $q->where('tipo', $aviso->tipo)->orWhere('carrera_id', $aviso->carrera_id))
            ->with(['carrera', 'sede'])
            ->latest()
            ->take(3)
            ->get();

        return view('cent74.aviso-show', compact('aviso', 'relacionados'));
    }

    public function mesas(): View
    {
        // Próximas mesas desde eventos públicos
        $proximasMesas = CentEvento::where('activo', true)
            ->where('publico', true)
            ->whereIn('tipo', ['mesa', 'inscripcion'])
            ->where('fecha_inicio', '>=', now()->startOfDay())
            ->with(['sede', 'carrera'])
            ->orderBy('fecha_inicio')
            ->take(12)
            ->get();

        // Avisos tipo "mesa" e "inscripcion" públicos
        $avisosMesas = AvisoCent::vigentes()
            ->publicos()
            ->whereIn('tipo', ['mesa', 'inscripcion', 'regularidad'])
            ->with(['carrera', 'sede'])
            ->orderBy('destacado', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('cent74.mesas', compact('proximasMesas', 'avisosMesas'));
    }

    public function contacto(): View
    {
        return view('cent74.contacto', [
            'sedes'       => CentSede::where('activa', true)->orderBy('orden')->orderBy('nombre')->get(),
            'siteSetting' => SiteSetting::current(),
        ]);
    }

    public function preinscripcion(): View
    {
        return view('cent74.preinscripcion', [
            'carreras' => Carrera::where('active', true)->with('centSedes')->orderBy('name')->get(),
            'sedes' => CentSede::where('activa', true)->orderBy('orden')->orderBy('nombre')->get(),
        ]);
    }

    public function horarios(): View
    {
        return view('cent74.horarios', [
            'horarios' => CentHorario::activos()
                ->with(['sede', 'carrera'])
                ->orderBy('orden')
                ->latest()
                ->get(),
            'sedes' => CentSede::where('activa', true)->orderBy('orden')->orderBy('nombre')->get(),
            'carreras' => Carrera::where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function descargas(): View
    {
        return view('cent74.descargas', [
            'descargas' => CentDescarga::activos()
                ->with('carrera')
                ->orderBy('categoria')
                ->orderBy('orden')
                ->latest()
                ->get()
                ->groupBy('categoria'),
            'categorias' => CentDescarga::categorias(),
        ]);
    }

    public function consultaPreinscripcion(): View
    {
        return view('cent74.consulta-preinscripcion');
    }

    public function guardarPreinscripcion(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'carrera_id' => ['required', 'exists:carreras,id'],
            'cent_sede_id' => ['required', 'exists:cent_sedes,id'],
            'apellido_nombre' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'nacionalidad' => ['nullable', 'string', 'max:80'],
            'estado_civil' => ['nullable', 'string', 'max:80'],
            'tipo_documento' => ['required', 'string', 'max:20'],
            'dni' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:80'],
            'domicilio' => ['nullable', 'string', 'max:255'],
            'localidad' => ['nullable', 'string', 'max:120'],
            'establecimiento_laboral' => ['nullable', 'string', 'max:255'],
            'nivel_estudios' => ['nullable', 'string', 'max:120'],
            'titulo_secundario' => ['nullable', 'string', 'max:255'],
            'observaciones_alumno' => ['nullable', 'string', 'max:3000'],
            'archivo_dni' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'archivo_titulo' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'archivo_recibo' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'archivo_adicional' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
        ], [
            'archivo_dni.required' => 'Adjuntá una imagen o PDF de tu DNI.',
            'archivo_titulo.required' => 'Adjuntá el título secundario o constancia correspondiente.',
            'archivo_dni.max' => 'El DNI puede pesar hasta 4 MB.',
            'archivo_titulo.max' => 'El título o constancia puede pesar hasta 4 MB.',
            'archivo_recibo.max' => 'El recibo puede pesar hasta 4 MB.',
            'archivo_adicional.max' => 'El archivo adicional puede pesar hasta 4 MB.',
        ]);

        $carrera = Carrera::with('centSedes')->findOrFail($data['carrera_id']);
        $sedesHabilitadas = $carrera->centSedes->pluck('id');

        if ($sedesHabilitadas->isNotEmpty() && ! $sedesHabilitadas->contains((int) $data['cent_sede_id'])) {
            return back()
                ->withErrors(['cent_sede_id' => 'La sede seleccionada no dicta la carrera elegida.'])
                ->withInput();
        }

        $duplicada = PreinscripcionCent::where('dni', $data['dni'])
            ->where('ciclo_lectivo', (int) now()->format('Y'))
            ->whereIn('estado', ['pendiente', 'en_revision', 'aprobada', 'inscripta'])
            ->exists();

        if ($duplicada) {
            return back()
                ->withErrors(['dni' => 'Ya existe una preinscripción activa para este DNI en el ciclo lectivo actual.'])
                ->withInput();
        }

        do {
            $data['codigo'] = 'CENT-'.now()->format('Y').'-'.strtoupper(Str::random(6));
        } while (PreinscripcionCent::where('codigo', $data['codigo'])->exists());

        $data['ciclo_lectivo'] = (int) now()->format('Y');
        $data['user_id'] = auth()->id();

        foreach (['archivo_dni', 'archivo_titulo', 'archivo_recibo', 'archivo_adicional'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('cent/preinscripciones/'.now()->format('Y'), 'public');
            }
        }

        $preinscripcion = PreinscripcionCent::create($data);

        return redirect()->route('cent.preinscripcion.gracias', $preinscripcion->codigo);
    }

    public function consultarPreinscripcion(Request $request): View
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:40'],
            'dni' => ['required', 'string', 'max:30'],
        ]);

        $preinscripcion = $this->buscarPreinscripcion($data['codigo'], $data['dni']);

        return view('cent74.estado-preinscripcion', [
            'preinscripcion' => $preinscripcion,
            'dniConsulta' => $data['dni'],
        ]);
    }

    public function actualizarDocumentacionPreinscripcion(Request $request, string $codigo): RedirectResponse
    {
        $data = $request->validate([
            'dni' => ['required', 'string', 'max:30'],
            'archivo_dni' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'archivo_titulo' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'archivo_recibo' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'archivo_adicional' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'observaciones_alumno' => ['nullable', 'string', 'max:3000'],
        ], [
            'archivo_dni.max' => 'El DNI puede pesar hasta 4 MB.',
            'archivo_titulo.max' => 'El título o constancia puede pesar hasta 4 MB.',
            'archivo_recibo.max' => 'El recibo puede pesar hasta 4 MB.',
            'archivo_adicional.max' => 'El archivo adicional puede pesar hasta 4 MB.',
        ]);

        $preinscripcion = $this->buscarPreinscripcion($codigo, $data['dni']);

        if (in_array($preinscripcion->estado, ['inscripta', 'rechazada'], true)) {
            return back()->withErrors([
                'documentacion' => 'Esta preinscripción ya no permite actualizar documentación.',
            ]);
        }

        $updates = [];
        foreach (['archivo_dni', 'archivo_titulo', 'archivo_recibo', 'archivo_adicional'] as $field) {
            if ($request->hasFile($field)) {
                $updates[$field] = $request->file($field)->store('cent/preinscripciones/'.now()->format('Y'), 'public');
            }
        }

        if (filled($data['observaciones_alumno'] ?? null)) {
            $updates['observaciones_alumno'] = trim(
                (($preinscripcion->observaciones_alumno ?? '') !== '' ? $preinscripcion->observaciones_alumno."\n\n" : '').
                'Actualización del aspirante '.now()->format('d/m/Y H:i').': '.$data['observaciones_alumno']
            );
        }

        if ($updates === []) {
            return back()->withErrors([
                'documentacion' => 'Adjuntá al menos un archivo o una observación para actualizar la solicitud.',
            ]);
        }

        $updates['estado'] = 'en_revision';
        $preinscripcion->update($updates);

        return redirect()
            ->route('cent.preinscripcion.consulta')
            ->with('status', 'Documentación actualizada. La preinscripción quedó nuevamente en revisión.');
    }

    public function gracias(string $codigo): View
    {
        return view('cent74.gracias', [
            'preinscripcion' => PreinscripcionCent::where('codigo', $codigo)->with(['carrera', 'sede'])->firstOrFail(),
        ]);
    }

    public function ficha(string $codigo)
    {
        $preinscripcion = PreinscripcionCent::where('codigo', $codigo)->with(['carrera', 'sede'])->firstOrFail();

        $pdf = Pdf::loadView('cent74.pdf.preinscripcion', compact('preinscripcion'))
            ->setPaper('a4')
            ->setOptions(['isRemoteEnabled' => true, 'defaultFont' => 'sans-serif']);

        return $pdf->download('preinscripcion-'.$preinscripcion->codigo.'.pdf');
    }

    private function buscarPreinscripcion(string $codigo, string $dni): PreinscripcionCent
    {
        return PreinscripcionCent::where('codigo', trim($codigo))
            ->where('dni', trim($dni))
            ->with(['carrera', 'sede'])
            ->firstOrFail();
    }
}

