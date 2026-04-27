<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthThrottleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        RateLimiter::clear('afiliado-login|12345678|127.0.0.1');
        RateLimiter::clear('cent-login|alumno@example.com|127.0.0.1');
        RateLimiter::clear('afiliado-password-reset|nadie@example.com|127.0.0.1');
    }

    public function test_afiliado_login_blocks_repeated_failed_attempts(): void
    {
        User::factory()->create([
            'role' => 'afiliado',
            'dni' => '12345678',
            'password' => 'Password123',
            'active' => true,
        ]);

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->post(route('afiliados.login.submit'), [
                'numero_afiliado' => '12345678',
                'password' => 'incorrecta',
            ])->assertSessionHasErrors('numero_afiliado');
        }

        $this->post(route('afiliados.login.submit'), [
            'numero_afiliado' => '12345678',
            'password' => 'incorrecta',
        ])->assertTooManyRequests();
    }

    public function test_cent_login_blocks_repeated_failed_attempts(): void
    {
        User::factory()->create([
            'role' => 'alumno',
            'cent_role' => 'alumno',
            'email' => 'alumno@example.com',
            'password' => 'Password123',
            'active' => true,
        ]);

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->post(route('cent.login.submit'), [
                'identificador' => 'alumno@example.com',
                'password' => 'incorrecta',
            ])->assertSessionHasErrors('identificador');
        }

        $this->post(route('cent.login.submit'), [
            'identificador' => 'alumno@example.com',
            'password' => 'incorrecta',
        ])->assertTooManyRequests();
    }

    public function test_password_recovery_blocks_repeated_unknown_identifiers(): void
    {
        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->post(route('afiliados.password.email'), [
                'identificador' => 'nadie@example.com',
            ])->assertSessionHasErrors('identificador');
        }

        $this->post(route('afiliados.password.email'), [
            'identificador' => 'nadie@example.com',
        ])->assertTooManyRequests();
    }
}
