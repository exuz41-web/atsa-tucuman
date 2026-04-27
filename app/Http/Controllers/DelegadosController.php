<?php

namespace App\Http\Controllers;

use App\Models\Delegado;
use App\Models\Filial;

class DelegadosController extends Controller
{
    public function index()
    {
        $delegados = Delegado::query()
            ->with('filial')
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        $filiales = Filial::query()
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return view('delegados.index', compact('delegados', 'filiales'));
    }
}
