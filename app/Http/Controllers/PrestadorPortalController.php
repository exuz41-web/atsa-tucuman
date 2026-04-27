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
            'qr' => ['nullable', 'string', 'max:1000'],
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

        $orden->registrarEntrega($data['respuesta_prestador'] ?? null);

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
        $qrToken = $this->tokenDesdeQr($data['qr'] ?? null);

        if (blank($data['numero_afiliado'] ?? null) && blank($data['dni'] ?? null) && blank($qrToken)) {
            return null;
        }

        return User::query()
            ->with('filial')
            ->where(fn ($query) => $query->where('role', 'afiliado')->orWhereNotNull('numero_afiliado'))
            ->where(function ($query) use ($data, $qrToken): void {
                $query
                    ->when($data['numero_afiliado'] ?? null, fn ($query, string $numero) => $query->orWhere('numero_afiliado', $numero))
                    ->when($data['dni'] ?? null, fn ($query, string $dni) => $query->orWhere('dni', preg_replace('/\D+/', '', $dni)))
                    ->when($qrToken, fn ($query, string $token) => $query->orWhere('afiliado_public_token', $token)->orWhere('numero_afiliado', $token));
            })
            ->first();
    }

    private function tokenDesdeQr(?string $qr): ?string
    {
        if (blank($qr)) {
            return null;
        }

        $value = trim($qr);
        $path = parse_url($value, PHP_URL_PATH);

        if (is_string($path) && str_contains($path, '/verificar/')) {
            return basename($path);
        }

        return $value;
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
