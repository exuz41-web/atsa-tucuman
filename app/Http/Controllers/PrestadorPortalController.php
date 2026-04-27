<?php

namespace App\Http\Controllers;

use App\Models\OrdenPrestacion;
use App\Models\Prestador;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrestadorPortalController extends Controller
{
    public function index(string $token): View
    {
        $prestador = $this->prestador($token);

        return view('prestadores.portal', [
            'prestador' => $prestador,
            'ordenes' => $prestador->ordenesPrestacion()
                ->with(['afiliado', 'pedido', 'solicitudBeneficio.beneficio'])
                ->latest()
                ->get(),
        ]);
    }

    public function validar(Request $request, string $token): View
    {
        $prestador = $this->prestador($token);

        $data = $request->validate([
            'codigo' => ['nullable', 'string', 'max:255'],
            'numero_afiliado' => ['nullable', 'string', 'max:255'],
            'dni' => ['nullable', 'string', 'max:255'],
        ]);

        $afiliado = $this->buscarAfiliado($data);
        $ordenes = collect();

        if (! $afiliado && filled($data['codigo'] ?? null)) {
            $orden = OrdenPrestacion::query()
                ->where('prestador_id', $prestador->id)
                ->where('codigo', $data['codigo'])
                ->first();

            $afiliado = $orden?->afiliado;
        }

        if ($afiliado) {
            $ordenes = OrdenPrestacion::query()
                ->with(['pedido', 'solicitudBeneficio.beneficio'])
                ->where('prestador_id', $prestador->id)
                ->where('afiliado_id', $afiliado->id)
                ->whereIn('estado', ['emitida', 'aceptada', 'observada'])
                ->when($data['codigo'] ?? null, fn ($query, string $codigo) => $query->where('codigo', $codigo))
                ->latest()
                ->get();
        }

        return view('prestadores.validar', [
            'prestador' => $prestador,
            'afiliado' => $afiliado,
            'ordenes' => $ordenes,
            'busqueda' => $data,
        ]);
    }

    public function aceptar(string $token, OrdenPrestacion $orden): RedirectResponse
    {
        $prestador = $this->prestador($token);
        abort_unless($orden->prestador_id === $prestador->id, 403);

        if ($orden->estado === 'emitida') {
            $orden->update([
                'estado' => 'aceptada',
                'aceptada_at' => now(),
            ]);
        }

        return back()->with('status', 'Orden aceptada.');
    }

    public function entregar(Request $request, string $token, OrdenPrestacion $orden): RedirectResponse
    {
        $prestador = $this->prestador($token);
        abort_unless($orden->prestador_id === $prestador->id, 403);
        abort_unless(in_array($orden->estado, ['emitida', 'aceptada', 'observada'], true), 403);

        $data = $request->validate([
            'respuesta_prestador' => ['nullable', 'string', 'max:2000'],
        ]);

        $afiliado = $orden->afiliado;
        abort_unless($this->afiliadoHabilitado($afiliado), 422, 'El afiliado no está habilitado.');

        $orden->update([
            'estado' => 'entregada',
            'entregada_at' => now(),
            'respuesta_prestador' => $data['respuesta_prestador'] ?? $orden->respuesta_prestador,
        ]);

        $orden->pedido?->update(['estado' => 'entregado', 'entregado_at' => now()]);
        $orden->solicitudBeneficio?->update(['estado' => 'entregada', 'entregado_at' => now()]);

        return redirect()
            ->route('prestadores.portal', $prestador->portal_token)
            ->with('status', 'Entrega registrada correctamente.');
    }

    private function prestador(string $token): Prestador
    {
        return Prestador::query()
            ->where('portal_token', $token)
            ->where('activo', true)
            ->firstOrFail();
    }

    private function buscarAfiliado(array $data): ?User
    {
        if (blank($data['numero_afiliado'] ?? null) && blank($data['dni'] ?? null)) {
            return null;
        }

        return User::query()
            ->with('filial')
            ->where(fn ($query) => $query->where('role', 'afiliado')->orWhereNotNull('numero_afiliado'))
            ->when($data['numero_afiliado'] ?? null, fn ($query, string $numero) => $query->where('numero_afiliado', $numero))
            ->when($data['dni'] ?? null, fn ($query, string $dni) => $query->where('dni', preg_replace('/\D+/', '', $dni)))
            ->first();
    }

    private function afiliadoHabilitado(?User $afiliado): bool
    {
        if (! $afiliado) {
            return false;
        }

        if ($afiliado->active === false || $afiliado->estado_afiliado !== 'activo') {
            return false;
        }

        if (! $afiliado->carnet_activo) {
            return false;
        }

        return ! $afiliado->carnet_vencimiento || $afiliado->carnet_vencimiento->gte(now()->startOfDay());
    }
}
