<?php

namespace App\Http\Controllers;

use App\Models\Descarga;
use App\Models\SolicitudAfiliacion;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SolicitudAfiliacionController extends Controller
{
    public function create(): View
    {
        $formularioPdf = Descarga::query()
            ->where('active', true)
            ->where('category', 'formularios')
            ->where('title', 'like', '%afili%')
            ->latest()
            ->first();

        return view('afiliacion.create', compact('formularioPdf'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'apellido_nombre' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'nacionalidad' => ['nullable', 'string', 'max:255'],
            'estado_civil' => ['nullable', 'string', 'max:255'],
            'tipo_documento' => ['required', Rule::in(['DNI', 'LC', 'LE', 'Pasaporte'])],
            'numero_documento' => ['required', 'string', 'max:30'],
            'establecimiento' => ['nullable', 'string', 'max:255'],
            'condicion_institucion' => ['nullable', 'string', 'max:255'],
            'nivel' => ['nullable', 'string', 'max:255'],
            'legajo' => ['nullable', 'string', 'max:255'],
            'profesion' => ['nullable', 'string', 'max:255'],
            'domicilio' => ['nullable', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'filial_preferida' => ['nullable', 'string', 'max:255'],
            'nombre_afiliador' => ['nullable', 'string', 'max:255'],
            'celular_afiliador' => ['nullable', 'string', 'max:255'],
            'dni_frente' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'dni_dorso' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'recibo_sueldo' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'formulario_firmado' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'archivo_adicional' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'acepta_declaracion' => ['accepted'],
        ], [
            'acepta_declaracion.accepted' => 'Debes aceptar la declaracion para enviar la solicitud.',
            'dni_frente.required' => 'Adjunta el frente del DNI.',
            'dni_dorso.required' => 'Adjunta el dorso del DNI.',
            'recibo_sueldo.required' => 'Adjunta el recibo de sueldo.',
        ]);

        $dni = preg_replace('/\D+/', '', $data['numero_documento']);

        if (User::where('dni', $dni)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['numero_documento' => 'Ya existe un afiliado registrado con este DNI.']);
        }

        $solicitudExistente = SolicitudAfiliacion::where('numero_documento', $dni)
            ->whereIn('estado', ['pendiente', 'en_revision', 'observada', 'aprobada'])
            ->first();

        if ($solicitudExistente) {
            return back()
                ->withInput()
                ->withErrors(['numero_documento' => 'Ya existe una solicitud activa para este DNI.']);
        }

        $path = 'afiliaciones/' . $dni . '-' . now()->format('YmdHis');

        // Almacenar archivos adjuntos
        $dniFrente    = $request->file('dni_frente')->store($path, 'public');
        $dniDorso     = $request->file('dni_dorso')->store($path, 'public');
        $reciboSueldo = $request->file('recibo_sueldo')->store($path, 'public');
        $formularioFirmado  = $request->file('formulario_firmado')?->store($path, 'public');
        $archivoAdicional   = $request->file('archivo_adicional')?->store($path, 'public');

        // Eliminar las entradas de archivo del array validado
        // (contienen UploadedFile objects, no rutas) antes del create
        unset($data['dni_frente'], $data['dni_dorso'], $data['recibo_sueldo'],
              $data['formulario_firmado'], $data['archivo_adicional'],
              $data['acepta_declaracion']);

        $solicitud = SolicitudAfiliacion::create([
            ...$data,
            'numero_documento'   => $dni,
            'estado'             => 'pendiente',
            'acepta_declaracion' => true,
            'dni_frente'         => $dniFrente,
            'dni_dorso'          => $dniDorso,
            'recibo_sueldo'      => $reciboSueldo,
            'formulario_firmado' => $formularioFirmado,
            'archivo_adicional'  => $archivoAdicional,
        ]);

        // Generar PDF institucional con DomPDF — no crítico
        try {
            $pdfPath = $path . '/solicitud.pdf';
            $pdf = Pdf::loadView('pdf.solicitud-afiliacion', ['solicitud' => $solicitud])
                ->setPaper('a4', 'portrait');

            Storage::disk('public')->put($pdfPath, $pdf->output());
            $solicitud->update(['pdf_path' => $pdfPath]);
        } catch (\Throwable $e) {
            \Log::warning('SolicitudAfiliacion: no se pudo generar PDF — ' . $e->getMessage());
        }

        // Notificar admins — no crítico, no debe interrumpir el flujo
        try {
            $admins = User::where('role', 'admin')->get();

            if ($admins->isNotEmpty()) {
                Notification::make()
                    ->title('Nueva solicitud de afiliación')
                    ->body($solicitud->apellido_nombre . ' envió una solicitud de afiliación.')
                    ->icon('heroicon-o-user-plus')
                    ->warning()
                    ->actions([
                        NotificationAction::make('ver')
                            ->label('Revisar')
                            ->url(url('/admin/solicitudes-afiliacion/' . $solicitud->id . '/edit')),
                    ])
                    ->sendToDatabase($admins, true);
            }
        } catch (\Throwable $e) {
            \Log::warning('SolicitudAfiliacion: no se pudo enviar notificación admin — ' . $e->getMessage());
        }

        return redirect()
            ->route('afiliacion.gracias')
            ->with('solicitud_id', $solicitud->id);
    }

    public function pdf(string $codigo)
    {
        $solicitud = SolicitudAfiliacion::query()
            ->where('numero_documento', $codigo)
            ->orWhere('id', $codigo)
            ->firstOrFail();

        // Si ya hay PDF generado, servirlo directo
        if ($solicitud->pdf_path && Storage::disk('public')->exists($solicitud->pdf_path)) {
            return Storage::disk('public')->download(
                $solicitud->pdf_path,
                'solicitud-afiliacion-' . $solicitud->numero_documento . '.pdf'
            );
        }

        // Fallback: regenerar on-the-fly
        $pdf = Pdf::loadView('pdf.solicitud-afiliacion', ['solicitud' => $solicitud])
            ->setPaper('a4', 'portrait');

        return $pdf->download('solicitud-afiliacion-' . $solicitud->numero_documento . '.pdf');
    }

    public function thanks(): View
    {
        return view('afiliacion.gracias');
    }
}
