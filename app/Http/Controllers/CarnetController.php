<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\CarnetSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarnetController extends Controller
{
    public function index()
    {
        $afiliado = auth()->user()->load('filial');

        if (! $afiliado->numero_afiliado) {
            return redirect()->route('afiliados.dashboard')
                ->with('error', 'Tu carnet aún no fue emitido. Contactá a tu filial.');
        }

        $urlVerificacion = CarnetSupport::verificationUrl($afiliado);
        $qrCode = CarnetSupport::qrBase64($urlVerificacion, 220);

        return view('afiliados.carnet', compact('afiliado', 'qrCode', 'urlVerificacion'));
    }

    public function descargar()
    {
        $afiliado = auth()->user()->load('filial');

        if (! $afiliado->numero_afiliado) {
            abort(403, 'Carnet no disponible');
        }

        $urlVerificacion = CarnetSupport::verificationUrl($afiliado);
        $qrPng = CarnetSupport::qrPng($urlVerificacion, 300);
        $qrCode = base64_encode($qrPng);

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.carnet', compact('afiliado', 'qrCode'))
                ->setPaper([0, 0, 255, 153], 'landscape')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'sans-serif',
                ]);

            return $pdf->download('carnet-atsa-'.$afiliado->numero_afiliado.'.pdf');
        }

        $binary = CarnetSupport::fallbackPdf($afiliado, $qrPng);

        return response($binary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="carnet-atsa-'.$afiliado->numero_afiliado.'.pdf"',
        ]);
    }

    public function descargarImagen()
    {
        $afiliado = auth()->user()->load('filial');

        if (! $afiliado->numero_afiliado) {
            abort(403);
        }

        $urlVerificacion = CarnetSupport::verificationUrl($afiliado);
        $qrPng = CarnetSupport::qrPng($urlVerificacion, 300);

        // Usamos el método interno de CarnetSupport que ya dibuja el carnet.
        $reflection = new \ReflectionClass(CarnetSupport::class);
        $method = $reflection->getMethod('cardJpeg');
        $method->setAccessible(true);
        $imageData = $method->invoke(null, $afiliado, $qrPng);

        return response($imageData, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'attachment; filename="carnet-atsa-'.$afiliado->numero_afiliado.'.jpg"',
        ]);
    }

    public function verificar(string $token)
    {
        $afiliado = User::with('filial')
            ->where('afiliado_public_token', $token)
            ->orWhere('numero_afiliado', $token)
            ->first();

        if (! $afiliado) {
            return view('carnet.no-encontrado');
        }

        $estado = $afiliado->carnet_activo ? 'activo' : 'inactivo';
        $vencido = $afiliado->carnet_vencimiento && $afiliado->carnet_vencimiento->lt(now()->startOfDay());
        $urlVerificacion = CarnetSupport::verificationUrl($afiliado);
        $qrCode = CarnetSupport::qrBase64($urlVerificacion, 360);

        return view('carnet.verificar', compact('afiliado', 'estado', 'vencido', 'qrCode', 'urlVerificacion'));
    }

    public function walletData()
    {
        $user = auth()->user()->load('filial');

        return response()->json([
            'organizationName' => 'ATSA Tucumán',
            'description' => 'Carnet Digital de Afiliado',
            'logoText' => 'ATSA',
            'foregroundColor' => 'rgb(255, 255, 255)',
            'backgroundColor' => 'rgb(30, 58, 95)',
            'labelColor' => 'rgb(73, 190, 255)',
            'fields' => [
                'primaryFields' => [
                    ['key' => 'name', 'label' => 'Afiliado', 'value' => $user->name],
                ],
                'secondaryFields' => [
                    ['key' => 'number', 'label' => 'N° CARNET', 'value' => $user->numero_afiliado],
                ],
                'auxiliaryFields' => [
                    ['key' => 'dni', 'label' => 'DNI', 'value' => $user->dni],
                    ['key' => 'filial', 'label' => 'FILIAL', 'value' => $user->filial?->name ?? 'Sede Central'],
                ],
                'backFields' => [
                    ['key' => 'info', 'label' => 'Información', 'value' => 'Este carnet es personal e intransferible. Válido para beneficios gremiales.'],
                    ['key' => 'address', 'label' => 'Sede', 'value' => 'Paraguay y Thames, San Miguel de Tucumán'],
                ],
            ],
            'barcode' => [
                'format' => 'PKBarcodeFormatQR',
                'message' => route('carnet.verificar', $user->numero_afiliado),
                'messageEncoding' => 'iso-8859-1',
            ],
        ]);
    }

    public function subirFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|max:2048|mimes:jpg,jpeg,png',
        ]);

        $afiliado = auth()->user();

        if ($afiliado->foto_perfil) {
            Storage::disk('public')->delete($afiliado->foto_perfil);
        }

        $path = $request->file('foto')->store('fotos-afiliados/'.$afiliado->id, 'public');
        $afiliado->update(['foto_perfil' => $path]);

        return back()->with('success', 'Foto actualizada correctamente');
    }
}
