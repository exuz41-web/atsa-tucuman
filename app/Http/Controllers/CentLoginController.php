<?php

namespace App\Http\Controllers;

use App\Models\MatriculaCent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CentLoginController extends Controller
{
    public function show(): View|RedirectResponse
    {
        $user = Auth::user();
        $centRole = $user?->cent_role ?: $user?->role;

        if (Auth::check() && in_array($centRole, ['alumno', 'docente', 'coordinador', 'directivo', 'admin'], true)) {
            return redirect()->route('cent.portal');
        }

        return view('cent74.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'identificador' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $identificador = trim($data['identificador']);

        $user = User::where('email', $identificador)
            ->orWhere('dni', $identificador)
            ->first();

        if (! $user) {
            $user = MatriculaCent::where('legajo', $identificador)
                ->with('alumno')
                ->first()?->alumno;
        }

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return back()
                ->withErrors(['identificador' => 'Los datos ingresados no son correctos.'])
                ->onlyInput('identificador');
        }

        $centRole = $user->cent_role ?: $user->role;

        if (! in_array($centRole, ['alumno', 'docente', 'coordinador', 'directivo', 'admin'], true)) {
            return back()
                ->withErrors(['identificador' => 'Este usuario no tiene acceso al portal académico.'])
                ->onlyInput('identificador');
        }

        if (! $user->active) {
            return back()
                ->withErrors(['identificador' => 'La cuenta se encuentra inactiva. Consultá en administración.'])
                ->onlyInput('identificador');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('cent.portal'));
    }

    public function portal(): RedirectResponse
    {
        $user = auth()->user();
        $role = $user?->cent_role ?: $user?->role;

        return match ($role) {
            'docente' => redirect()->route('cent.docente.dashboard'),
            'coordinador', 'directivo', 'admin' => redirect()->route('cent.directivo.dashboard'),
            default => redirect()->route('cent.alumno.dashboard'),
        };
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('cent.login');
    }
}
