<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Str;

class CarnetSupport
{
    public static function verificationUrl(User $afiliado): string
    {
        return route('carnet.verificar', $afiliado->afiliado_public_token ?: $afiliado->numero_afiliado);
    }

    public static function qrPng(string $url, int $size = 240): string
    {
        if (class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
            return (string) \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                ->size($size)
                ->errorCorrection('H')
                ->generate($url);
        }

        $endpoint = 'https://api.qrserver.com/v1/create-qr-code/?format=png&size='.$size.'x'.$size.'&ecc=H&data='.rawurlencode($url);
        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $binary = curl_exec($ch);
        curl_close($ch);

        return is_string($binary) && $binary !== '' ? $binary : self::placeholderQr($size);
    }

    public static function qrBase64(string $url, int $size = 240): string
    {
        return base64_encode(self::qrPng($url, $size));
    }

    public static function initials(string $name): string
    {
        return Str::of($name)
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(fn (string $part): string => Str::upper(Str::substr($part, 0, 1)))
            ->implode('');
    }

    public static function maskedDni(?string $dni): string
    {
        if (blank($dni)) {
            return 'No registrado';
        }

        return '***'.substr(preg_replace('/\D+/', '', $dni), -4);
    }

    public static function fotoUrl(User $afiliado): ?string
    {
        if (! $afiliado->foto_perfil) {
            return null;
        }

        $path = ltrim(str_replace('\\', '/', $afiliado->foto_perfil), '/');

        return '/storage/'.$path;
    }

    public static function fotoPath(User $afiliado): ?string
    {
        return $afiliado->foto_perfil ? storage_path('app/public/'.$afiliado->foto_perfil) : null;
    }

    public static function fallbackPdf(User $afiliado, string $qrPng): string
    {
        $jpeg = self::cardJpeg($afiliado, $qrPng);
        $width = 255;
        $height = 153;
        $imageInfo = getimagesizefromstring($jpeg);
        $imageWidth = $imageInfo[0] ?? 510;
        $imageHeight = $imageInfo[1] ?? 306;
        $content = "q\n{$width} 0 0 {$height} 0 0 cm\n/Im0 Do\nQ\n";

        $objects = [
            "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n",
            "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n",
            "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 {$width} {$height}] /Resources << /XObject << /Im0 4 0 R >> >> /Contents 5 0 R >>\nendobj\n",
            "4 0 obj\n<< /Type /XObject /Subtype /Image /Width {$imageWidth} /Height {$imageHeight} /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length ".strlen($jpeg)." >>\nstream\n".$jpeg."\nendstream\nendobj\n",
            "5 0 obj\n<< /Length ".strlen($content)." >>\nstream\n".$content."endstream\nendobj\n",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object;
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= str_pad((string) $offsets[$i], 10, '0', STR_PAD_LEFT)." 00000 n \n";
        }

        $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";

        return $pdf;
    }

    private static function cardJpeg(User $afiliado, string $qrPng): string
    {
        $w = 510;
        $h = 306;
        $img = imagecreatetruecolor($w, $h);

        $white = imagecolorallocate($img, 255, 255, 255);
        $page = imagecolorallocate($img, 248, 251, 255);
        $blue = imagecolorallocate($img, 93, 135, 255);
        $navy = imagecolorallocate($img, 30, 58, 95);
        $muted = imagecolorallocate($img, 90, 106, 133);
        $green = imagecolorallocate($img, 19, 222, 185);
        $sky = imagecolorallocate($img, 73, 190, 255);
        $dark = imagecolorallocate($img, 42, 53, 71);
        $border = imagecolorallocate($img, 229, 234, 239);
        $soft = imagecolorallocate($img, 236, 242, 255);

        imagefilledrectangle($img, 0, 0, $w, $h, $white);
        imagefilledellipse($img, 440, 35, 140, 140, $soft);
        imagerectangle($img, 0, 0, $w - 1, $h - 1, $border);

        imagestring($img, 5, 28, 20, 'ATSA', $navy);
        imagestring($img, 2, 30, 42, 'Tucuman', $sky);
        imagestring($img, 2, 28, 62, 'CREDENCIAL PERSONAL E INTRANSFERIBLE', $muted);
        imagefilledrectangle($img, 360, 22, 485, 50, $navy);
        imagestring($img, 3, 374, 30, 'AFILIADO GREMIAL', $white);

        self::drawCircle($img, 82, 118, 44, $soft);
        self::drawCircle($img, 82, 118, 38, imagecolorallocate($img, 223, 231, 255));
        $fotoPath = self::fotoPath($afiliado);
        if ($fotoPath && is_file($fotoPath) && ($foto = @imagecreatefromstring((string) file_get_contents($fotoPath)))) {
            imagecopyresampled($img, $foto, 44, 80, 0, 0, 76, 76, imagesx($foto), imagesy($foto));
            imagedestroy($foto);
        } else {
            imagestring($img, 5, 67, 108, self::initials($afiliado->name), $blue);
        }

        imagestring($img, 2, 52, 177, 'NRO CARNET', $muted);
        imagestring($img, 4, 34, 195, $afiliado->numero_afiliado ?? 'Sin numero', $navy);
        for ($x = 42; $x < 122; $x += 6) {
            imagefilledrectangle($img, $x, 220, $x + (($x % 12) ? 1 : 3), 242, $navy);
        }

        imagefilledrectangle($img, 155, 78, $afiliado->carnet_activo ? 285 : 238, 102, $afiliado->carnet_activo ? imagecolorallocate($img, 230, 255, 250) : imagecolorallocate($img, 255, 241, 237));
        imagestring($img, 3, 166, 84, $afiliado->carnet_activo ? 'CARNET VALIDO' : 'INACTIVO', $afiliado->carnet_activo ? imagecolorallocate($img, 19, 163, 132) : $navy);
        imagestring($img, 5, 155, 116, Str::limit($afiliado->name, 25, ''), $dark);
        imagestring($img, 3, 155, 154, 'DNI:', $muted);
        imagestring($img, 3, 235, 154, $afiliado->dni ?? 'No registrado', $dark);
        imagestring($img, 3, 155, 178, 'Filial:', $muted);
        imagestring($img, 3, 235, 178, Str::limit($afiliado->filial?->name ?? 'Sede Central', 22, ''), $dark);
        imagestring($img, 3, 155, 202, 'Vence:', $muted);
        imagestring($img, 3, 235, 202, optional($afiliado->carnet_vencimiento)->format('d/m/Y') ?? ('31/12/'.date('Y')), $dark);

        if ($qr = @imagecreatefromstring($qrPng)) {
            imagefilledrectangle($img, 390, 145, 474, 229, $white);
            imagerectangle($img, 390, 145, 474, 229, $border);
            imagecopyresampled($img, $qr, 397, 152, 0, 0, 70, 70, imagesx($qr), imagesy($qr));
            imagedestroy($qr);
            imagestring($img, 1, 409, 232, 'Escanear', $muted);
        }

        imagefilledrectangle($img, 0, 264, $w, 270, $sky);
        imagefilledrectangle($img, 0, 270, $w, $h, $navy);
        imagestring($img, 3, 54, 284, 'ASOCIACION DE TRABAJADORES DE LA SANIDAD ARGENTINA - TUCUMAN', $white);

        ob_start();
        imagejpeg($img, null, 92);
        $jpeg = (string) ob_get_clean();
        imagedestroy($img);

        return $jpeg;
    }

    private static function drawCircle($img, int $cx, int $cy, int $r, int $color): void
    {
        imagefilledellipse($img, $cx, $cy, $r * 2, $r * 2, $color);
    }

    private static function placeholderQr(int $size): string
    {
        $img = imagecreatetruecolor($size, $size);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 15, 34, 54);
        imagefilledrectangle($img, 0, 0, $size, $size, $white);
        imagestring($img, 3, 10, (int) ($size / 2 - 8), 'QR no disponible', $black);
        ob_start();
        imagepng($img);
        $png = (string) ob_get_clean();
        imagedestroy($img);

        return $png;
    }
}

