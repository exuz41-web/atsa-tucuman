<?php

namespace App\Http\Controllers;

use App\Models\HotelConvenio;
use App\Models\PageSection;
use App\Models\TurismoConsulta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TurismoController extends Controller
{
    public function index()
    {
        $condiciones = PageSection::get('turismo', 'condiciones');
        $hotelesConvenio = HotelConvenio::query()
            ->where('activo', true)
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();

        return view('turismo.index', compact('condiciones', 'hotelesConvenio'));
    }

    public function consulta(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:120'],
            'telefono' => ['required', 'string', 'min:6', 'max:40'],
            'email' => ['nullable', 'email', 'max:150'],
            'dni' => ['nullable', 'string', 'max:20'],
            'numero_afiliado' => ['nullable', 'string', 'max:40'],
            'beneficio' => ['required', 'in:ciudad_deportiva,hotel_termas,convenios_fatsa,otro'],
            'fecha_estimada' => ['nullable', 'date', 'after_or_equal:today'],
            'mensaje' => ['required', 'string', 'min:10', 'max:2000'],
            'website' => ['nullable', 'size:0'],
        ], [
            'website.size' => 'No pudimos procesar la consulta.',
        ]);

        unset($validated['website']);

        TurismoConsulta::create($validated + ['estado' => 'pendiente']);

        return back()
            ->withFragment('consulta-turismo')
            ->with('turismo_success', 'Recibimos tu consulta turística. ATSA Tucumán se comunicará a la brevedad.');
    }
}
