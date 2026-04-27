<?php

namespace Tests\Feature;

use App\Models\Beneficio;
use App\Models\Pedido;
use App\Models\SolicitudBeneficio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AfiliadoPrivateFilesTest extends TestCase
{
    use RefreshDatabase;

    public function test_pedido_attachments_are_stored_privately(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $afiliado = User::factory()->create([
            'role' => 'afiliado',
            'active' => true,
        ]);

        $this->actingAs($afiliado)
            ->post(route('afiliados.pedidos.guardar'), [
                'tipo' => 'anteojos',
                'descripcion' => 'Solicitud de prueba para anteojos.',
                'archivo_dni' => UploadedFile::fake()->image('dni.jpg'),
                'archivo_recibo' => UploadedFile::fake()->create('recibo.pdf', 100, 'application/pdf'),
                'archivo_adicional' => UploadedFile::fake()->image('adicional.png'),
            ])
            ->assertRedirect('/afiliados/mis-pedidos');

        $pedido = Pedido::firstOrFail();

        Storage::disk('local')->assertExists($pedido->archivo_dni);
        Storage::disk('local')->assertExists($pedido->archivo_recibo);
        Storage::disk('local')->assertExists($pedido->archivo_adicional);
        Storage::disk('public')->assertMissing($pedido->archivo_dni);
        Storage::disk('public')->assertMissing($pedido->archivo_recibo);
        Storage::disk('public')->assertMissing($pedido->archivo_adicional);
    }

    public function test_solicitud_beneficio_attachments_are_stored_privately(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $afiliado = User::factory()->create([
            'role' => 'afiliado',
            'active' => true,
        ]);

        $beneficio = Beneficio::create([
            'titulo' => 'Ayuda escolar',
            'slug' => 'ayuda-escolar-'.Str::random(6),
            'categoria' => 'accion_social',
            'descripcion_corta' => 'Beneficio de prueba.',
            'activo' => true,
            'publico' => true,
        ]);

        $this->actingAs($afiliado)
            ->post(route('afiliados.beneficios.guardar', $beneficio), [
                'mensaje' => 'Solicito el beneficio con la documentación correspondiente.',
                'archivo_dni' => UploadedFile::fake()->image('dni.jpg'),
                'archivo_recibo' => UploadedFile::fake()->create('recibo.pdf', 100, 'application/pdf'),
                'archivo_adicional' => UploadedFile::fake()->image('adicional.png'),
            ])
            ->assertRedirect(route('afiliados.beneficios'));

        $solicitud = SolicitudBeneficio::firstOrFail();

        Storage::disk('local')->assertExists($solicitud->archivo_dni);
        Storage::disk('local')->assertExists($solicitud->archivo_recibo);
        Storage::disk('local')->assertExists($solicitud->archivo_adicional);
        Storage::disk('public')->assertMissing($solicitud->archivo_dni);
        Storage::disk('public')->assertMissing($solicitud->archivo_recibo);
        Storage::disk('public')->assertMissing($solicitud->archivo_adicional);
    }
}
