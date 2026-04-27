<?php

namespace App\Http\Controllers;

use App\Models\Documento;

class DocumentosController extends Controller
{
    public function index()
    {
        $documentos = Documento::query()
            ->where('activo', true)
            ->orderByDesc('anio')
            ->orderBy('titulo')
            ->get();

        return view('documentos.index', compact('documentos'));
    }
}
