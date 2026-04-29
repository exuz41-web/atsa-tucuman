<?php

namespace Tests\Feature;

use App\Models\OrdenPrestacion;
use App\Models\Pedido;
use App\Models\Prestador;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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
            'portal_username' => 'optica-centro',
            'portal_password' => Hash::make('secret123'),
        ]);

        $this->get(route('prestadores.portal', 'token-invalido'))->assertNotFound();
        $this->get(route('prestadores.portal', $prestador->portal_token))->assertRedirect(route('prestadores.login'));
        $this->loginPrestador($prestador);
        $this->get(route('prestadores.portal', $prestador->portal_token))->assertOk()->assertSee('Óptica Centro');

        $prestador->update(['activo' => false]);
        $this->get(route('prestadores.portal', $prestador->portal_token))->assertNotFound();
    }

    public function test_prestador_exposes_portal_url_and_can_regenerate_token(): void
    {
        $prestador = Prestador::create([
            'nombre' => 'Óptica Centro',
            'tipo' => 'optica',
            'activo' => true,
            'portal_username' => 'optica-centro',
            'portal_password' => Hash::make('secret123'),
        ]);

        $oldToken = $prestador->portal_token;
        $this->assertSame(route('prestadores.portal', $oldToken), $prestador->portalUrl());

        $prestador->update(['portal_token' => (string) \Illuminate\Support\Str::uuid()]);

        $prestador->refresh();
        $this->assertNotSame($oldToken, $prestador->portal_token);
        $this->get(route('prestadores.portal', $oldToken))->assertNotFound();
        $this->loginPrestador($prestador);
        $this->get($prestador->portalUrl())->assertOk();
    }

    public function test_prestador_logs_in_with_username_and_password(): void
    {
        $prestador = Prestador::create([
            'nombre' => 'Óptica Centro',
            'tipo' => 'optica',
            'activo' => true,
            'portal_username' => 'optica-centro',
            'portal_password' => Hash::make('secret123'),
        ]);

        $this->post(route('prestadores.login.submit'), [
            'usuario' => 'optica-centro',
            'password' => 'incorrecta',
        ])->assertSessionHasErrors('usuario');

        $this->post(route('prestadores.login.submit'), [
            'usuario' => 'optica-centro',
            'password' => 'secret123',
        ])->assertRedirect($prestador->portalUrl());

        $this->assertNotNull($prestador->fresh()->portal_last_login_at);
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

        $prestador = Prestador::create([
            'nombre' => 'Óptica Centro',
            'tipo' => 'optica',
            'activo' => true,
            'portal_username' => 'optica-centro',
            'portal_password' => Hash::make('secret123'),
        ]);
        $otroPrestador = Prestador::create([
            'nombre' => 'Farmacia Norte',
            'tipo' => 'farmacia',
            'activo' => true,
            'portal_username' => 'farmacia-norte',
            'portal_password' => Hash::make('secret123'),
        ]);

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

        $this->loginPrestador($prestador);

        $this->get(route('prestadores.validar', [
            'token' => $prestador->portal_token,
            'numero_afiliado' => 'A-100',
            'qr' => route('carnet.verificar', $afiliado->afiliado_public_token),
        ]))
            ->assertOk()
            ->assertSee('AFILIADO HABILITADO')
            ->assertSee($orden->codigo);

        $this->post(route('prestadores.ordenes.entregar', [$otroPrestador->portal_token, $orden]))
            ->assertRedirect(route('prestadores.login'));

        $this->post(route('prestadores.ordenes.entregar', [$prestador->portal_token, $orden]), [
            'respuesta_prestador' => 'Intento sin QR.',
        ])
            ->assertSessionHasErrors('qr');

        $this->post(route('prestadores.ordenes.entregar', [$prestador->portal_token, $orden]), [
            'respuesta_prestador' => 'Entregado con receta validada.',
            'qr' => route('carnet.verificar', $afiliado->afiliado_public_token),
        ])
            ->assertRedirect(route('prestadores.portal', $prestador->portal_token));

        $this->assertSame('entregada', $orden->fresh()->estado);
        $this->assertSame('entregado', $pedido->fresh()->estado);
        $this->assertSame('Entregado con receta validada.', $orden->fresh()->respuesta_prestador);
        $this->assertStringContainsString('Óptica Centro', (string) $pedido->fresh()->observacion_afiliado);
        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $afiliado->id,
        ]);
    }

    public function test_prestador_can_validate_affiliate_from_carnet_qr_url(): void
    {
        $afiliado = User::factory()->create([
            'role' => 'afiliado',
            'active' => true,
            'estado_afiliado' => 'activo',
            'numero_afiliado' => 'A-101',
            'dni' => '30111223',
            'carnet_activo' => true,
            'carnet_vencimiento' => now()->addMonth(),
        ]);

        $prestador = Prestador::create([
            'nombre' => 'Óptica Centro',
            'tipo' => 'optica',
            'activo' => true,
            'portal_username' => 'optica-centro',
            'portal_password' => Hash::make('secret123'),
        ]);

        $orden = OrdenPrestacion::create([
            'prestador_id' => $prestador->id,
            'afiliado_id' => $afiliado->id,
            'tipo' => 'anteojos',
            'detalle' => 'Entrega de lentes.',
        ]);

        $this->loginPrestador($prestador);

        $this->get(route('prestadores.validar', [
            'token' => $prestador->portal_token,
            'qr' => route('carnet.verificar', $afiliado->afiliado_public_token),
        ]))
            ->assertOk()
            ->assertSee('AFILIADO HABILITADO')
            ->assertSee($orden->codigo);
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

        $prestador = Prestador::create([
            'nombre' => 'Óptica Centro',
            'tipo' => 'optica',
            'activo' => true,
            'portal_username' => 'optica-centro',
            'portal_password' => Hash::make('secret123'),
        ]);

        $orden = OrdenPrestacion::create([
            'prestador_id' => $prestador->id,
            'afiliado_id' => $afiliado->id,
            'tipo' => 'anteojos',
            'detalle' => 'Entrega de lentes.',
        ]);

        $this->loginPrestador($prestador);

        $this->post(route('prestadores.ordenes.entregar', [$prestador->portal_token, $orden]))
            ->assertSessionHasErrors('qr');

        $this->post(route('prestadores.ordenes.entregar', [$prestador->portal_token, $orden]), [
            'qr' => route('carnet.verificar', $afiliado->afiliado_public_token),
        ])->assertStatus(422);

        $this->assertSame('emitida', $orden->fresh()->estado);
    }

    public function test_prestador_manifest_is_installable_scope(): void
    {
        $this->get(route('prestadores.manifest'))
            ->assertOk()
            ->assertJsonPath('name', 'ATSA Prestadores')
            ->assertJsonPath('scope', '/prestadores');
    }

    private function loginPrestador(Prestador $prestador): void
    {
        $this->withSession(['prestador_id' => $prestador->id]);
    }
}
