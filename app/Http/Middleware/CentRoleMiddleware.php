<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CentRoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('cent.login');
        }

        $role = $user->cent_role ?: $user->role;

        if (! in_array($role, $roles, true)) {
            abort(403, 'No tenes permisos para acceder a esta seccion del CENT.');
        }

        if (! $user->active) {
            Auth::logout();

            return redirect()
                ->route('cent.login')
                ->withErrors(['identificador' => 'La cuenta se encuentra inactiva. Consulta en administracion.']);
        }

        return $next($request);
    }
}
