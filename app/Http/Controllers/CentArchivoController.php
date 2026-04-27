<?php

namespace App\Http\Controllers;

use App\Models\CentCuota;
use App\Models\CentEntregaTrabajo;
use App\Models\CentLegajoDocumento;
use App\Models\CentMaterial;
use App\Models\CentTrabajoPractico;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CentArchivoController extends Controller
{
    public function legajo(CentLegajoDocumento $documento): StreamedResponse
    {
        $user = auth()->user();
        $role = $user->cent_role ?: $user->role;

        abort_unless(
            $documento->user_id === $user->id || in_array($role, ['admin', 'directivo', 'coordinador'], true),
            403
        );

        return $this->download($documento->archivo, 'legajo-'.$documento->id.$this->extension($documento->archivo));
    }

    public function cuotaComprobante(CentCuota $cuota): StreamedResponse
    {
        $user = auth()->user();
        $role = $user->cent_role ?: $user->role;

        abort_unless(
            $cuota->alumno_id === $user->id || in_array($role, ['admin', 'directivo', 'coordinador'], true),
            403
        );

        return $this->download($cuota->comprobante, 'comprobante-cuota-'.$cuota->id.$this->extension($cuota->comprobante));
    }

    public function material(CentMaterial $material): StreamedResponse
    {
        $user = auth()->user();
        $role = $user->cent_role ?: $user->role;
        $comision = $material->comision;

        $puedeDescargar = in_array($role, ['admin', 'directivo', 'coordinador'], true)
            || ($role === 'docente' && $comision?->docente_id === $user->id)
            || (
                $role === 'alumno'
                && $material->publicado
                && $user->inscripcionesAcademicas()->where('comision_id', $material->comision_id)->exists()
            );

        abort_unless($puedeDescargar, 403);

        return $this->download($material->archivo, 'material-'.$material->id.$this->extension($material->archivo));
    }

    public function trabajoConsigna(CentTrabajoPractico $trabajo): StreamedResponse
    {
        $user = auth()->user();
        $role = $user->cent_role ?: $user->role;
        $comision = $trabajo->comision;

        $puedeDescargar = in_array($role, ['admin', 'directivo', 'coordinador'], true)
            || ($role === 'docente' && $comision?->docente_id === $user->id)
            || (
                $role === 'alumno'
                && $trabajo->publicado
                && $user->inscripcionesAcademicas()->where('comision_id', $trabajo->comision_id)->exists()
            );

        abort_unless($puedeDescargar, 403);

        return $this->download($trabajo->archivo_consigna, 'consigna-'.$trabajo->id.$this->extension($trabajo->archivo_consigna));
    }

    public function entrega(CentEntregaTrabajo $entrega): StreamedResponse
    {
        $user = auth()->user();
        $role = $user->cent_role ?: $user->role;
        $comision = $entrega->trabajo?->comision;

        $puedeDescargar = $entrega->alumno_id === $user->id
            || in_array($role, ['admin', 'directivo', 'coordinador'], true)
            || ($role === 'docente' && $comision?->docente_id === $user->id);

        abort_unless($puedeDescargar, 403);

        return $this->download($entrega->archivo, 'entrega-'.$entrega->id.$this->extension($entrega->archivo));
    }

    private function download(?string $path, string $downloadName): StreamedResponse
    {
        abort_if(blank($path), 404);

        return $this->diskFor($path)->download($path, $downloadName);
    }

    private function diskFor(string $path): FilesystemAdapter
    {
        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local');
        }

        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public');
    }

    private function extension(?string $path): string
    {
        if (! $path || ! str_contains($path, '.')) {
            return '';
        }

        return '.'.pathinfo($path, PATHINFO_EXTENSION);
    }
}
