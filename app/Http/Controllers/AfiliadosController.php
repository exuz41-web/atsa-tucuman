<?php

namespace App\Http\Controllers;

use App\Models\Beneficio;

class AfiliadosController extends Controller
{
    public function index()
    {
        $beneficios = Beneficio::query()
            ->activos()
            ->publicos()
            ->ordenados()
            ->get();

        $beneficiosDestacados = $beneficios->where('destacado', true)->take(4);
        $beneficiosPorCategoria = $beneficios->groupBy('categoria');

        return view('afiliados.index', compact('beneficios', 'beneficiosDestacados', 'beneficiosPorCategoria'));
    }
}
