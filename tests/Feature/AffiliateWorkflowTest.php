<?php

namespace Tests\Feature;

use App\Filament\Resources\PedidoResource;
use App\Models\ExpedienteMovimiento;
use App\Models\Pedido;
use App\Models\Secretaria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliateWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_pedido_is_assigned_to_suggested_secretaria_and_tracks_public_observation(): void
    {
        $accionSocial = Secretaria::create([
            'nombre' => 'Secretaría de Previsión y Acción Social',
            'slug' => 'secretaria-de-prevision-y-accion-social',
            'activa' => true,
        ]);

        $afiliado = User::factory()->create(['role' => 'afiliado', 'active' => true]);
        $admin = User::factory()->create(['role' => 'admin', 'active' => true]);

        $pedido = Pedido::create([
            'afiliado_id' => $afiliado->id,
            'tipo' => 'anteojos',
            'descripcion' => 'Necesito renovar lentes.',
        ]);

        $this->assertSame($accionSocial->id, $pedido->secretaria_id);

        $this->actingAs($admin);
        $pedido->update([
            'estado' => 'observado',
            'observaciones' => 'Nota interna para acción social.',
            'observacion_afiliado' => 'Falta adjuntar receta actualizada.',
        ]);

        $this->assertDatabaseHas('expediente_movimientos', [
            'expediente_type' => Pedido::class,
            'expediente_id' => $pedido->id,
            'estado_anterior' => 'pendiente',
            'estado_nuevo' => 'observado',
            'observacion_afiliado' => 'Falta adjuntar receta actualizada.',
        ]);

        $this->actingAs($afiliado)
            ->get(route('afiliados.pedidos'))
            ->assertOk()
            ->assertSee('Falta adjuntar receta actualizada.')
            ->assertDontSee('Nota interna para acción social.');
    }

    public function test_secretaria_user_only_sees_its_assigned_pedidos(): void
    {
        $accionSocial = Secretaria::create([
            'nombre' => 'Secretaría de Previsión y Acción Social',
            'slug' => 'secretaria-de-prevision-y-accion-social',
            'activa' => true,
        ]);

        $turismo = Secretaria::create([
            'nombre' => 'Secretaría de Turismo y Vivienda',
            'slug' => 'secretaria-de-turismo-y-vivienda',
            'activa' => true,
        ]);

        $afiliado = User::factory()->create(['role' => 'afiliado', 'active' => true]);
        $secretariaUser = User::factory()->create([
            'role' => 'afiliado',
            'perfil_interno' => 'secretaria',
            'secretaria_id' => $accionSocial->id,
            'active' => true,
        ]);

        $pedidoAccionSocial = Pedido::create([
            'afiliado_id' => $afiliado->id,
            'tipo' => 'anteojos',
            'secretaria_id' => $accionSocial->id,
            'descripcion' => 'Pedido visible.',
        ]);

        $pedidoTurismo = Pedido::create([
            'afiliado_id' => $afiliado->id,
            'tipo' => 'turismo',
            'secretaria_id' => $turismo->id,
            'descripcion' => 'Pedido oculto.',
        ]);

        $this->actingAs($secretariaUser);
        $ids = PedidoResource::getEloquentQuery()->pluck('id')->all();

        $this->assertContains($pedidoAccionSocial->id, $ids);
        $this->assertNotContains($pedidoTurismo->id, $ids);
        $this->assertSame(0, ExpedienteMovimiento::count());
    }
}
