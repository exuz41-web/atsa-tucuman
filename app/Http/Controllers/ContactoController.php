<?php

namespace App\Http\Controllers;

use App\Mail\ContactoMail;
use App\Models\Filial;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    public function index()
    {
        $filiales = Filial::query()
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return view('contacto.index', compact('filiales'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre'  => ['required', 'string', 'min:3', 'max:100'],
            'email'   => ['required', 'email', 'max:150'],
            'telefono'=> ['nullable', 'string', 'max:30'],
            'asunto'  => ['required', 'string', 'max:100'],
            'mensaje' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        try {
            $setting = SiteSetting::current();
            $to = filter_var($setting->email ?? '', FILTER_VALIDATE_EMAIL)
                ? $setting->email
                : config('mail.from.address', 'contacto@atsatucuman.org');

            Mail::to($to)->send(new ContactoMail($validated));

            return response()->json([
                'success' => true,
                'message' => '¡Mensaje enviado! Te responderemos a la brevedad.',
            ]);
        } catch (\Throwable) {
            return response()->json([
                'success' => false,
                'message' => 'No pudimos enviar tu mensaje. Por favor llamanos al 0381 4331665.',
            ], 500);
        }
    }
}

