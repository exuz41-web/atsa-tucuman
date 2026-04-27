<?php

namespace App\Http\Controllers;

use App\Models\Efemeride;

class EfemeridesController extends Controller
{
    public function index()
    {
        $efemerides = Efemeride::query()
            ->where('activo', true)
            ->orderBy('mes')
            ->orderBy('dia')
            ->get();

        return view('efemerides.index', compact('efemerides'));
    }
}
