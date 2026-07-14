<?php

namespace Database\Seeders;

use App\Models\OrdenPrestacion;
use App\Models\Pedido;
use App\Models\Prestador;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoAccessSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@atsa.com'],
            [
                'name' => 'Admin ATSA',
                'password' => Hash::make(env('DEMO_ADMIN_PASSWORD', 'Admin1234!')),
                'role' => 'admin',
                'perfil_interno' => 'ninguno',
                'active' => true,
            ]
        );

        $afiliado = User::updateOrCreate(
            ['email' => 'marcelabulacio493@gmail.com'],
            [
                'name' => 'Marcela Roxana Bulacio',
                'password' => Hash::make(env('DEMO_AFILIADO_PASSWORD', 'Afiliado1234!')),
                'role' => 'afiliado',
                'perfil_interno' => 'ninguno',
                'dni' => '12345678',
                'numero_afiliado' => 'ATSA2024-00001',
                'active' => true,
                'estado_afiliado' => 'activo',
                'tipo_afiliado' => 'estatal',
                'categoria_laboral' => 'Afiliada demo',
                'carnet_activo' => true,
                'carnet_vencimiento' => now()->addYear(),
                'carnet_emitido_at' => now(),
            ]
        );

        $prestador = Prestador::updateOrCreate(
            ['portal_username' => 'optica-demo'],
            [
                'nombre' => 'Óptica Demo ATSA',
                'tipo' => 'optica',
                'cuit' => '30700000001',
                'responsable' => 'Prestador Demo',
                'email' => 'optica.demo@atsa.com',
                'telefono' => '3815550000',
                'localidad' => 'San Miguel de Tucumán',
                'provincia' => 'Tucumán',
                'activo' => true,
                'portal_password' => Hash::make(env('DEMO_PRESTADOR_PASSWORD', 'Prestador1234!')),
            ]
        );

        $pedido = Pedido::firstOrCreate(
            [
                'afiliado_id' => $afiliado->id,
                'tipo' => 'anteojos',
                'estado' => 'aprobado',
            ],
            [
                'descripcion' => 'Pedido demo de lentes recetados para validar el circuito con prestador.',
                'observaciones' => 'Demo para presentación ATSA.',
                'observacion_afiliado' => 'Tu pedido de lentes fue aprobado y derivado al prestador.',
                'aprobado_at' => now(),
            ]
        );

        OrdenPrestacion::firstOrCreate(
            [
                'prestador_id' => $prestador->id,
                'afiliado_id' => $afiliado->id,
                'pedido_id' => $pedido->id,
                'tipo' => 'anteojos',
            ],
            [
                'estado' => 'emitida',
                'detalle' => 'Entrega demo de lentes recetados.',
                'observaciones_internas' => 'Orden demo generada para presentación.',
                'emitida_at' => now(),
                'emitida_por' => $admin->id,
            ]
        );
    }
}
