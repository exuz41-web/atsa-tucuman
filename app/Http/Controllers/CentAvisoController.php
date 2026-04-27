<?php

namespace App\Http\Controllers;

use App\Models\AvisoCent;
use Illuminate\View\View;

class CentAvisoController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $centRole = $user->cent_role ?: $user->role;

        $matriculas = $user->matriculasCent()->get(['carrera_id', 'cent_sede_id']);
        $carreraIds = $matriculas->pluck('carrera_id')->filter()->unique();
        $sedeIds = $matriculas->pluck('cent_sede_id')->filter()->unique();

        $avisos = AvisoCent::vigentes()
            ->whereIn('rol_destino', ['todos', $centRole])
            ->where(fn ($query) => $query->whereNull('carrera_id')->orWhereIn('carrera_id', $carreraIds))
            ->where(fn ($query) => $query->whereNull('cent_sede_id')->orWhereIn('cent_sede_id', $sedeIds))
            ->with(['carrera', 'sede', 'creadoPor'])
            ->orderByDesc('destacado')
            ->latest()
            ->paginate(12);

        return view('cent74.avisos', [
            'avisos' => $avisos,
            'centRole' => $centRole,
        ]);
    }
}
