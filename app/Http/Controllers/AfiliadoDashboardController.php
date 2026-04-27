<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Descarga;
use App\Models\Beneficio;
use App\Models\Pedido;
use App\Models\Post;
use App\Models\SolicitudBeneficio;
use App\Models\Testimonio;
use App\Models\User;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AfiliadoDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $notificaciones = $user->notifications()->latest()->take(5)->get();

        // Marcar como leídas al visualizar el dashboard
        $user->unreadNotifications()->latest()->take(5)->update(['read_at' => now()]);

        return view('afiliados.dashboard', [
            'pedidosPendientes' => Pedido::where('afiliado_id', $user->id)
                ->whereIn('estado', ['pendiente', 'en_revision'])
                ->count(),
            'consultas' => Consulta::where('afiliado_id', $user->id)->count(),
            'documentos' => Descarga::where('active', true)->count(),
            'pedidosRecientes' => Pedido::where('afiliado_id', $user->id)
                ->latest()
                ->take(5)
                ->get(),
            'notificaciones' => $notificaciones,
            'ultimoAcceso' => now(),
            'novedades' => Post::where('category', 'gremial')
                ->whereNotNull('published_at')
                ->latest('published_at')
                ->take(3)
                ->get(),
        ]);
    }

    public function datos(): View
    {
        return view('afiliados.datos', [
            'user' => Auth::user()->load('filial'),
        ]);
    }

    public function actualizarDatos(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($data);

        return back()->with('status', 'Tus datos fueron actualizados.');
    }

    public function pedidos(): View
    {
        return view('afiliados.pedidos', [
            'pedidos' => Pedido::where('afiliado_id', Auth::id())
                ->latest()
                ->get(),
        ]);
    }

    public function nuevoPedido(): View
    {
        return view('afiliados.nuevo-pedido');
    }

    public function guardarPedido(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'tipo' => ['required', Rule::in(['anteojos', 'protesis', 'medicamentos', 'ayuda_economica', 'otro'])],
            'descripcion' => ['required', 'string'],
            'archivo_dni' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'archivo_recibo' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'archivo_adicional' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $path = 'pedidos/'.$user->id;

        $pedido = Pedido::create([
            'afiliado_id' => $user->id,
            'tipo' => $data['tipo'],
            'descripcion' => $data['descripcion'],
            'archivo_dni' => $request->file('archivo_dni')->store($path, 'local'),
            'archivo_recibo' => $request->file('archivo_recibo')->store($path, 'local'),
            'archivo_adicional' => $request->file('archivo_adicional')?->store($path, 'local'),
        ]);

        Notification::make()
            ->title('Nuevo pedido de afiliado')
            ->body($user->name.' cargó una solicitud de '.str_replace('_', ' ', $pedido->tipo).'.')
            ->icon('heroicon-o-clipboard-document-list')
            ->warning()
            ->actions([
                NotificationAction::make('ver')
                    ->label('Ver pedido')
                    ->url(url('/admin/pedidos/'.$pedido->id.'/edit')),
            ])
            ->sendToDatabase(User::where('role', 'admin')->get(), true);

        return redirect('/afiliados/mis-pedidos')->with('status', 'Tu solicitud fue enviada.');
    }

    public function consultas(): View
    {
        return view('afiliados.consultas', [
            'consultas' => Consulta::where('afiliado_id', Auth::id())
                ->latest()
                ->get(),
        ]);
    }

    public function guardarConsulta(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tipo' => ['required', Rule::in(['turno', 'consulta'])],
            'asunto' => ['required', 'string', 'max:255'],
            'mensaje' => ['required', 'string'],
            'fecha_solicitada' => ['nullable', 'date'],
        ]);

        $consulta = Consulta::create($data + [
            'afiliado_id' => Auth::id(),
        ]);

        Notification::make()
            ->title('Nueva consulta de afiliado')
            ->body(Auth::user()->name.' envió una consulta: '.$consulta->asunto)
            ->icon('heroicon-o-chat-bubble-left-right')
            ->info()
            ->actions([
                NotificationAction::make('ver')
                    ->label('Ver consulta')
                    ->url(url('/admin/consultas/'.$consulta->id.'/edit')),
            ])
            ->sendToDatabase(User::where('role', 'admin')->get(), true);

        return back()->with('status', 'Tu consulta fue enviada.');
    }

    public function beneficios(): View
    {
        $beneficios = Beneficio::query()
            ->activos()
            ->ordenados()
            ->get();

        return view('afiliados.beneficios', [
            'beneficios' => $beneficios,
            'beneficiosDestacados' => $beneficios->where('destacado', true)->take(4),
            'beneficiosPorCategoria' => $beneficios->groupBy('categoria'),
            'solicitudesBeneficios' => SolicitudBeneficio::query()
                ->with('beneficio')
                ->where('afiliado_id', Auth::id())
                ->latest()
                ->take(6)
                ->get(),
        ]);
    }

    public function solicitarBeneficio(Beneficio $beneficio): View
    {
        abort_unless($beneficio->activo, 404);

        return view('afiliados.solicitar-beneficio', [
            'beneficio' => $beneficio,
        ]);
    }

    public function guardarSolicitudBeneficio(Request $request, Beneficio $beneficio): RedirectResponse
    {
        abort_unless($beneficio->activo, 404);

        $user = Auth::user();

        $data = $request->validate([
            'mensaje' => ['required', 'string', 'min:10', 'max:2000'],
            'archivo_dni' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'archivo_recibo' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'archivo_adicional' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $path = 'solicitudes-beneficios/'.$user->id;

        $solicitud = SolicitudBeneficio::create([
            'beneficio_id' => $beneficio->id,
            'afiliado_id' => $user->id,
            'mensaje' => $data['mensaje'],
            'archivo_dni' => $request->file('archivo_dni')?->store($path, 'local'),
            'archivo_recibo' => $request->file('archivo_recibo')?->store($path, 'local'),
            'archivo_adicional' => $request->file('archivo_adicional')?->store($path, 'local'),
            'estado' => 'pendiente',
        ]);

        Notification::make()
            ->title('Nueva solicitud de beneficio')
            ->body($user->name.' solicitó: '.$beneficio->titulo)
            ->icon('heroicon-o-gift')
            ->warning()
            ->actions([
                NotificationAction::make('ver')
                    ->label('Ver solicitud')
                    ->url(url('/admin/solicitudes-beneficios/'.$solicitud->id.'/edit')),
            ])
            ->sendToDatabase(User::where('role', 'admin')->get(), true);

        return redirect()
            ->route('afiliados.beneficios')
            ->with('status', 'Tu solicitud de beneficio fue enviada.');
    }

    public function descargas(): View
    {
        return view('afiliados.descargas', [
            'descargas' => Descarga::where('active', true)
                ->latest()
                ->get()
                ->groupBy('category'),
        ]);
    }

    public function testimonio(): View
    {
        return view('afiliados.testimonio', [
            'testimonio' => Testimonio::where('afiliado_id', Auth::id())->latest()->first(),
            'user' => Auth::user()->load('filial'),
        ]);
    }

    public function guardarTestimonio(Request $request): RedirectResponse
    {
        $user = Auth::user()->load('filial');

        $data = $request->validate([
            'texto' => ['required', 'string', 'min:20', 'max:500'],
            'cargo' => ['nullable', 'string', 'max:255'],
        ], [
            'texto.min' => 'El testimonio debe tener al menos 20 caracteres.',
            'texto.max' => 'El testimonio no puede superar los 500 caracteres.',
        ]);

        $testimonio = Testimonio::updateOrCreate(
            ['afiliado_id' => $user->id],
            [
                'nombre' => $user->name,
                'cargo' => $data['cargo'] ?: ($user->categoria_laboral ?: 'Afiliado'),
                'filial' => $user->filial?->name,
                'texto' => $data['texto'],
                'foto' => $user->foto_perfil,
                'activo' => false,
                'estado' => 'pendiente',
            ]
        );

        Notification::make()
            ->title('Nuevo testimonio de afiliado')
            ->body($user->name.' envió un testimonio para revisar.')
            ->icon('heroicon-o-chat-bubble-left-ellipsis')
            ->info()
            ->actions([
                NotificationAction::make('ver')
                    ->label('Revisar testimonio')
                    ->url(url('/admin/testimonios/'.$testimonio->id.'/edit')),
            ])
            ->sendToDatabase(User::where('role', 'admin')->get(), true);

        return back()->with('status', 'Tu testimonio fue enviado y queda pendiente de aprobación.');
    }
}
