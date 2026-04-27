<?php

namespace App\Http\Controllers;

use App\Models\OrdenPrestacion;
use App\Models\Pedido;
use App\Models\SolicitudBeneficio;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AtencionReporteController extends Controller
{
    public function ordenes(Request $request): StreamedResponse
    {
        $query = OrdenPrestacion::query()
            ->with(['prestador', 'afiliado', 'pedido', 'solicitudBeneficio.beneficio'])
            ->when($request->string('estado')->isNotEmpty(), fn ($query) => $query->where('estado', $request->string('estado')))
            ->when($request->date('desde'), fn ($query, $desde) => $query->whereDate('created_at', '>=', $desde))
            ->when($request->date('hasta'), fn ($query, $hasta) => $query->whereDate('created_at', '<=', $hasta))
            ->latest();

        return $this->csv('ordenes-prestacion.csv', [
            'codigo',
            'estado',
            'tipo',
            'prestador',
            'afiliado',
            'numero_afiliado',
            'pedido',
            'solicitud_beneficio',
            'emitida_at',
            'aceptada_at',
            'entregada_at',
            'detalle',
            'respuesta_prestador',
        ], $query->cursor()->map(fn (OrdenPrestacion $orden): array => [
            $orden->codigo,
            $orden->estado,
            $orden->tipo,
            $orden->prestador?->nombre,
            $orden->afiliado?->name,
            $orden->afiliado?->numero_afiliado,
            $orden->pedido_id ? Pedido::numero($orden->pedido_id) : null,
            $orden->solicitud_beneficio_id ? SolicitudBeneficio::numero($orden->solicitud_beneficio_id) : null,
            optional($orden->emitida_at)->format('Y-m-d H:i:s'),
            optional($orden->aceptada_at)->format('Y-m-d H:i:s'),
            optional($orden->entregada_at)->format('Y-m-d H:i:s'),
            $orden->detalle,
            $orden->respuesta_prestador,
        ]));
    }

    public function pedidos(Request $request): StreamedResponse
    {
        $query = Pedido::query()
            ->with(['afiliado', 'secretaria', 'asignadoA'])
            ->when($request->string('estado')->isNotEmpty(), fn ($query) => $query->where('estado', $request->string('estado')))
            ->when($request->date('desde'), fn ($query, $desde) => $query->whereDate('created_at', '>=', $desde))
            ->when($request->date('hasta'), fn ($query, $hasta) => $query->whereDate('created_at', '<=', $hasta))
            ->latest();

        return $this->csv('pedidos-atencion.csv', [
            'numero',
            'estado',
            'tipo',
            'secretaria',
            'responsable',
            'afiliado',
            'numero_afiliado',
            'created_at',
            'aprobado_at',
            'entregado_at',
            'descripcion',
            'observacion_afiliado',
            'observaciones_internas',
        ], $query->cursor()->map(fn (Pedido $pedido): array => [
            Pedido::numero($pedido->id),
            $pedido->estado,
            $pedido->tipo,
            $pedido->secretaria?->nombre,
            $pedido->asignadoA?->name,
            $pedido->afiliado?->name,
            $pedido->afiliado?->numero_afiliado,
            optional($pedido->created_at)->format('Y-m-d H:i:s'),
            optional($pedido->aprobado_at)->format('Y-m-d H:i:s'),
            optional($pedido->entregado_at)->format('Y-m-d H:i:s'),
            $pedido->descripcion,
            $pedido->observacion_afiliado,
            $pedido->observaciones,
        ]));
    }

    public function solicitudesBeneficios(Request $request): StreamedResponse
    {
        $query = SolicitudBeneficio::query()
            ->with(['beneficio', 'afiliado', 'secretaria', 'asignadoA'])
            ->when($request->string('estado')->isNotEmpty(), fn ($query) => $query->where('estado', $request->string('estado')))
            ->when($request->date('desde'), fn ($query, $desde) => $query->whereDate('created_at', '>=', $desde))
            ->when($request->date('hasta'), fn ($query, $hasta) => $query->whereDate('created_at', '<=', $hasta))
            ->latest();

        return $this->csv('solicitudes-beneficios.csv', [
            'numero',
            'estado',
            'beneficio',
            'categoria',
            'secretaria',
            'responsable',
            'afiliado',
            'numero_afiliado',
            'created_at',
            'aprobado_at',
            'entregado_at',
            'mensaje',
            'observacion_afiliado',
            'observaciones_internas',
        ], $query->cursor()->map(fn (SolicitudBeneficio $solicitud): array => [
            SolicitudBeneficio::numero($solicitud->id),
            $solicitud->estado,
            $solicitud->beneficio?->titulo,
            $solicitud->beneficio?->categoria,
            $solicitud->secretaria?->nombre,
            $solicitud->asignadoA?->name,
            $solicitud->afiliado?->name,
            $solicitud->afiliado?->numero_afiliado,
            optional($solicitud->created_at)->format('Y-m-d H:i:s'),
            optional($solicitud->aprobado_at)->format('Y-m-d H:i:s'),
            optional($solicitud->entregado_at)->format('Y-m-d H:i:s'),
            $solicitud->mensaje,
            $solicitud->observacion_afiliado,
            $solicitud->observaciones,
        ]));
    }

    private function csv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
