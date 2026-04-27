<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class AfiliadoLoginController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect('/afiliados/dashboard');
        }

        return view('afiliados.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $this->ensureNotRateLimited($request, 'afiliado-login');

        $credentials = $request->validate([
            'numero_afiliado' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $documento = trim($credentials['numero_afiliado']);
        $user = User::where('numero_afiliado', $documento)
            ->orWhere('dni', $documento)
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            $this->hitLimiter($request, 'afiliado-login');

            return back()
                ->withErrors(['numero_afiliado' => 'Los datos ingresados no son correctos.'])
                ->onlyInput('numero_afiliado');
        }

        if (! in_array($user->role, ['afiliado', 'admin'], true)) {
            $this->hitLimiter($request, 'afiliado-login');

            return back()
                ->withErrors(['numero_afiliado' => 'Este usuario no tiene acceso al area de afiliados.'])
                ->onlyInput('numero_afiliado');
        }

        if (! $user->active) {
            $this->hitLimiter($request, 'afiliado-login');

            return back()
                ->withErrors(['numero_afiliado' => 'Tu cuenta se encuentra inactiva. Comunicate con ATSA Tucuman.'])
                ->onlyInput('numero_afiliado');
        }

        RateLimiter::clear($this->throttleKey($request, 'afiliado-login'));

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended('/afiliados/dashboard');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect('/afiliados/dashboard');
        }

        return view('afiliados.registro');
    }

    public function register(Request $request): RedirectResponse
    {
        return redirect()
            ->route('afiliacion.create')
            ->with('status', 'Para darte de alta en el portal primero necesitamos revisar tu solicitud de afiliacion.');
    }

    public function showForgotPassword(): View
    {
        return view('afiliados.recuperar-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $this->ensureNotRateLimited($request, 'afiliado-password-reset');

        $data = $request->validate([
            'identificador' => ['required', 'string'],
        ]);

        $identificador = trim($data['identificador']);
        $user = User::where('email', $identificador)
            ->orWhere('dni', $identificador)
            ->orWhere('numero_afiliado', $identificador)
            ->first();

        if (! $user) {
            $this->hitLimiter($request, 'afiliado-password-reset');

            return back()
                ->withErrors(['identificador' => 'No encontramos una cuenta con esos datos.'])
                ->onlyInput('identificador');
        }

        RateLimiter::clear($this->throttleKey($request, 'afiliado-password-reset'));

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetUrl = route('afiliados.password.reset', ['token' => $token, 'email' => $user->email]);

        return back()
            ->with('status', 'Generamos un enlace para recuperar tu contrasena.')
            ->with('reset_link', $resetUrl);
    }

    public function showResetPassword(Request $request, string $token): View
    {
        return view('afiliados.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $this->ensureNotRateLimited($request, 'afiliado-password-update');

        $data = $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->numbers()],
        ], [
            'password.confirmed' => 'La confirmacion de contrasena no coincide.',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $data['email'])->first();

        if (! $record || ! Hash::check($data['token'], $record->token)) {
            $this->hitLimiter($request, 'afiliado-password-update');

            return back()->withErrors(['email' => 'El enlace de recuperacion no es valido.']);
        }

        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            $this->hitLimiter($request, 'afiliado-password-update');
            DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

            return redirect()->route('afiliados.password.request')
                ->withErrors(['identificador' => 'El enlace vencio. Genera uno nuevo.']);
        }

        RateLimiter::clear($this->throttleKey($request, 'afiliado-password-update'));

        $user = User::where('email', $data['email'])->firstOrFail();
        $user->update(['password' => $data['password']]);

        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

        return redirect()->route('afiliados.login')
            ->with('status', 'Contrasena actualizada. Ya podes ingresar.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/afiliados/login');
    }

    private function ensureNotRateLimited(Request $request, string $prefix): void
    {
        abort_if(
            RateLimiter::tooManyAttempts($this->throttleKey($request, $prefix), 5),
            429,
            'Demasiados intentos. Esperá un minuto antes de volver a probar.'
        );
    }

    private function hitLimiter(Request $request, string $prefix): void
    {
        RateLimiter::hit($this->throttleKey($request, $prefix), 60);
    }

    private function throttleKey(Request $request, string $prefix): string
    {
        $identifier = $request->input('numero_afiliado', $request->input('identificador', $request->input('email', '')));

        return $prefix.'|'.Str::lower((string) $identifier).'|'.$request->ip();
    }
}
