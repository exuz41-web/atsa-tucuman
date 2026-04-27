<?php

namespace App\Http\Controllers;

use App\Models\EscalaSalarial;

class EscalasController extends Controller
{
    public function index()
    {
        $escalas = EscalaSalarial::query()
            ->where('activo', true)
            ->orderByDesc('vigente_desde')
            ->get();

        return view('escalas.index', compact('escalas'));
    }
}
