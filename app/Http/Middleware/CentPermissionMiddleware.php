<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CentPermissionMiddleware
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('cent.login');
        }

        if (! $user->active) {
            Auth::logout();

            return redirect()
                ->route('cent.login')
                ->withErrors(['identificador' => 'La cuenta se encuentra inactiva. Consulta en administracion.']);
        }

        if (! $user->hasAnyPermission($permissions)) {
            abort(403, 'No tenés permisos para acceder a esta sección del CENT.');
        }

        return $next($request);
    }
}
