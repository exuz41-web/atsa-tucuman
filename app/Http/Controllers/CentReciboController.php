<?php

namespace App\Http\Controllers;

use App\Models\CentRecibo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CentReciboController extends Controller
{
    public function pdf(CentRecibo $recibo): Response
    {
        abort_unless($recibo->alumno_id === auth()->id() || in_array(auth()->user()->cent_role ?: auth()->user()->role, ['admin', 'directivo', 'coordinador'], true), 403);

        $recibo->load(['alumno', 'cuota.matricula.carrera', 'cuota.matricula.sede']);
        $url = route('cent.recibos.verificar', $recibo->qr_token);
        $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($url));

        return Pdf::loadView('cent74.pdf.recibo-cuota', compact('recibo', 'qrCode', 'url'))
            ->download('recibo-'.$recibo->numero.'.pdf');
    }

    public function verificar(string $token)
    {
        $recibo = CentRecibo::where('qr_token', $token)->with(['alumno', 'cuota'])->first();

        return view('cent74.verificar-recibo', compact('recibo'));
    }
}
