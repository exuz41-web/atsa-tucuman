<?php

namespace App\Http\Controllers;

use App\Models\Filial;

class FilialesController extends Controller
{
    public function index()
    {
        $filiales = Filial::query()
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return view('filiales.index', compact('filiales'));
    }
}
