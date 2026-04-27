<?php

namespace Tests\Feature;

use App\Models\OrdenPrestacion;
use App\Models\Pedido;
use App\Models\Prestador;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrestadorPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_prestador_portal_requires_valid_active_token(): void
    {
        $prestador = Prestador::create([
            'nombre' => 'Óptica Centro',
            'tipo' => 'optica',
            'activo' => true,
        ]);

        $this->get(route('prestadores.portal', 'token-invalido'))->assertNotFound();
        $this->get(route('prestadores.portal', $prestador->portal_token))->assertOk()->assertSee('Óptica Centro');

        $prestador->update(['activo' => false]);
        $this->get(route('prestadores.portal', $prestador->portal_token))->assertNotFound();
    }

    public function test_prestador_can_validate_and_deliver_only_its_own_order(): void
    {
        $afiliado = User::factory()->create([
            'role' => 'afiliado',
            'active' => true,
            'estado_afiliado' => 'activo',
            'numero_afiliado' => 'A-100',
            'dni' => '30111222',
            'carnet_activo' => true,
            'carnet_vencimiento' => now()->addMonth(),
        ]);

        $prestador = Prestador::create(['nombre' => 'Óptica Centro', 'tipo' => 'optica', 'activo' => true]);
        $otroPrestador = Prestador::create(['nombre' => 'Farmacia Norte', 'tipo' => 'farmacia', 'activo' => true]);

        $pedido = Pedido::create([
            'afiliado_id' => $afiliado->id,
            'tipo' => 'anteojos',
            'descripcion' => 'Lentes recetados.',
            'estado' => 'aprobado',
            'aprobado_at' => now(),
        ]);

        $orden = OrdenPrestacion::create([
            'prestador_id' => $prestador->id,
            'afiliado_id' => $afiliado->id,
            'pedido_id' => $pedido->id,
            'tipo' => 'anteojos',
            'detalle' => 'Entrega de lentes.',
        ]);

        $this->get(route('prestadores.validar', [
            'token' => $prestador->portal_token,
            'numero_afiliado' => 'A-100',
        ]))
            ->assertOk()
            ->assertSee('AFILIADO HABILITADO')
            ->assertSee($orden->codigo);

        $this->post(route('prestadores.ordenes.entregar', [$otroPrestador->portal_token, $orden]))
            ->assertForbidden();

        $this->post(route('prestadores.ordenes.entregar', [$prestador->portal_token, $orden]), [
            'respuesta_prestador' => 'Entregado con receta validada.',
        ])
            ->assertRedirect(route('prestadores.portal', $prestador->portal_token));

        $this->assertSame('entregada', $orden->fresh()->estado);
        $this->assertSame('entregado', $pedido->fresh()->estado);
    }

    public function test_prestador_cannot_deliver_when_affiliate_is_not_enabled(): void
    {
        $afiliado = User::factory()->create([
            'role' => 'afiliado',
            'active' => true,
            'estado_afiliado' => 'suspendido',
            'numero_afiliado' => 'A-200',
            'carnet_activo' => true,
            'carnet_vencimiento' => now()->addMonth(),
        ]);

        $prestador = Prestador::create(['nombre' => 'Óptica Centro', 'tipo' => 'optica', 'activo' => true]);

        $orden = OrdenPrestacion::create([
            'prestador_id' => $prestador->id,
            'afiliado_id' => $afiliado->id,
            'tipo' => 'anteojos',
            'detalle' => 'Entrega de lentes.',
        ]);

        $this->post(route('prestadores.ordenes.entregar', [$prestador->portal_token, $orden]))
            ->assertStatus(422);

        $this->assertSame('emitida', $orden->fresh()->estado);
    }
}
