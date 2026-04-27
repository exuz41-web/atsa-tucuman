<?php

namespace Tests\Feature;

use App\Models\OrdenPrestacion;
use App\Models\Pedido;
use App\Models\Prestador;
use App\Models\SolicitudBeneficio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AtencionReporteTest extends TestCase
{
    use RefreshDatabase;

    public function test_atencion_reports_require_permission(): void
    {
        $afiliado = User::factory()->create(['role' => 'afiliado', 'active' => true]);
        $admin = User::factory()->create(['role' => 'admin', 'active' => true]);

        $this->actingAs($afiliado)
            ->get(route('panel.reportes.atencion.ordenes'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('panel.reportes.atencion.ordenes'))
            ->assertOk();
    }

    public function test_exports_orders_pedidos_and_benefit_requests_as_csv(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'active' => true]);
        $afiliado = User::factory()->create([
            'role' => 'afiliado',
            'active' => true,
            'numero_afiliado' => 'A-300',
        ]);
        $prestador = Prestador::create(['nombre' => 'Óptica Centro', 'tipo' => 'optica', 'activo' => true]);

        $pedido = Pedido::create([
            'afiliado_id' => $afiliado->id,
            'tipo' => 'anteojos',
            'descripcion' => 'Pedido de lentes.',
        ]);

        OrdenPrestacion::create([
            'prestador_id' => $prestador->id,
            'afiliado_id' => $afiliado->id,
            'pedido_id' => $pedido->id,
            'tipo' => 'anteojos',
            'detalle' => 'Entrega de lentes.',
        ]);

        SolicitudBeneficio::create([
            'afiliado_id' => $afiliado->id,
            'beneficio_id' => \App\Models\Beneficio::create([
                'titulo' => 'Ayuda social',
                'slug' => 'ayuda-social-test',
                'categoria' => 'accion_social',
                'descripcion_corta' => 'Prueba',
                'activo' => true,
            ])->id,
            'mensaje' => 'Solicito ayuda social.',
        ]);

        $this->actingAs($admin);

        $ordenesCsv = $this->get(route('panel.reportes.atencion.ordenes'))->assertOk()->streamedContent();
        $this->assertStringContainsString('codigo', $ordenesCsv);
        $this->assertStringContainsString('Óptica Centro', $ordenesCsv);

        $pedidosCsv = $this->get(route('panel.reportes.atencion.pedidos'))->assertOk()->streamedContent();
        $this->assertStringContainsString('numero', $pedidosCsv);
        $this->assertStringContainsString('Pedido de lentes.', $pedidosCsv);

        $solicitudesCsv = $this->get(route('panel.reportes.atencion.solicitudes-beneficios'))->assertOk()->streamedContent();
        $this->assertStringContainsString('beneficio', $solicitudesCsv);
        $this->assertStringContainsString('Ayuda social', $solicitudesCsv);
    }
}
