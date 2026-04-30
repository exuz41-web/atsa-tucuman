<?php

namespace Tests\Feature;

use App\Models\OrdenPrestacion;
use App\Models\Pedido;
use App\Models\Prestador;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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

        $prestador->update(['portal_token' => (string) Str::uuid()]);

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

    public function test_prestador_can_generate_generic_portal_access(): void
    {
        $prestador = Prestador::create([
            'nombre' => 'Sanatorio Modelo',
            'tipo' => 'salud',
            'activo' => true,
        ]);

        $credentials = $prestador->asegurarAccesoPortal();

        $this->assertSame('sanatorio-modelo', $credentials['usuario']);
        $this->assertNotEmpty($credentials['password']);
        $this->assertTrue($prestador->fresh()->tieneAccesoPortal());

        $this->post(route('prestadores.login.submit'), [
            'usuario' => $credentials['usuario'],
            'password' => $credentials['password'],
        ])->assertRedirect($prestador->fresh()->portalUrl());
    }

    public function test_command_generates_access_for_all_active_prestadores(): void
    {
        $activo = Prestador::create([
            'nombre' => 'Farmacia Popular',
            'tipo' => 'farmacia',
            'activo' => true,
        ]);

        $inactivo = Prestador::create([
            'nombre' => 'Comercio Pausado',
            'tipo' => 'comercio',
            'activo' => false,
        ]);

        Artisan::call('prestadores:generar-accesos');

        $this->assertTrue($activo->fresh()->tieneAccesoPortal());
        $this->assertFalse($inactivo->fresh()->tieneAccesoPortal());
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
            ->assertRedirect(route('prestadores.portal', $prestador->portal_token))
            ->assertSessionHas('status', 'QR validado: '.$afiliado->name.' está habilitado.');
    }

    public function test_prestador_validation_page_prioritizes_qr_scanner(): void
    {
        $prestador = Prestador::create([
            'nombre' => 'Óptica Centro',
            'tipo' => 'optica',
            'activo' => true,
            'portal_username' => 'optica-centro',
            'portal_password' => Hash::make('secret123'),
        ]);

        $this->loginPrestador($prestador);

        $this->get(route('prestadores.validar', ['token' => $prestador->portal_token, 'scan' => 1]))
            ->assertOk()
            ->assertSee('Escanear QR')
            ->assertSee('Escaneá el QR del carnet')
            ->assertDontSee('Tomar foto del QR')
            ->assertDontSee('Pegá el link', false);
    }

    public function test_delivery_from_order_code_requires_qr_before_submit_button(): void
    {
        $afiliado = User::factory()->create([
            'role' => 'afiliado',
            'active' => true,
            'estado_afiliado' => 'activo',
            'numero_afiliado' => 'A-102',
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
            'codigo' => $orden->codigo,
        ]))
            ->assertOk()
            ->assertSee('Validá el QR para entregar')
            ->assertSee($orden->codigo)
            ->assertDontSee('AFILIADO HABILITADO')
            ->assertDontSee($afiliado->name)
            ->assertDontSee('Registrar entrega');

        $this->get(route('prestadores.validar', [
            'token' => $prestador->portal_token,
            'codigo' => $orden->codigo,
            'qr' => route('carnet.verificar', $afiliado->afiliado_public_token),
        ]))
            ->assertRedirect(route('prestadores.portal', $prestador->portal_token))
            ->assertSessionHas('status', 'QR validado para '.$afiliado->name.' en la orden '.$orden->codigo.'.');
    }

    public function test_provider_orders_page_keeps_single_general_validation_button(): void
    {
        $prestador = Prestador::create([
            'nombre' => 'Óptica Centro',
            'tipo' => 'optica',
            'activo' => true,
            'portal_username' => 'optica-centro',
            'portal_password' => Hash::make('secret123'),
        ]);

        $afiliado = User::factory()->create([
            'role' => 'afiliado',
            'active' => true,
            'estado_afiliado' => 'activo',
        ]);

        OrdenPrestacion::create([
            'prestador_id' => $prestador->id,
            'afiliado_id' => $afiliado->id,
            'tipo' => 'anteojos',
        ]);

        $this->loginPrestador($prestador);

        $this->get(route('prestadores.portal', $prestador->portal_token))
            ->assertOk()
            ->assertSee('Escanear QR')
            ->assertSee(route('prestadores.validar', ['token' => $prestador->portal_token, 'codigo' => OrdenPrestacion::first()->codigo]), false)
            ->assertDontSee('scan=1')
            ->assertDontSee('Escanear QR y entregar');
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
