<?php

namespace App\Filament\Widgets;

use App\Models\Consulta;
use App\Models\Pedido;
use App\Models\Post;
use App\Models\SolicitudAfiliacion;
use App\Models\TurismoConsulta;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class UltimosMovimientos extends Widget
{
    protected static string $view = 'filament.widgets.ultimos-movimientos';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $pedidos = Pedido::with('afiliado')->latest()->take(6)->get()->map(fn (Pedido $p): array => [
            'tipo'        => 'Pedido',
            'descripcion' => 'PED-'.str_pad($p->id, 6, '0', STR_PAD_LEFT).' – '.str_replace('_', ' ', $p->tipo),
            'usuario'     => $p->afiliado?->name ?? 'Sin afiliado',
            'fecha'       => $p->created_at,
            'estado'      => $p->estado,
            'color'       => $this->colorPedido($p->estado),
            'url'         => url('/admin/pedidos/'.$p->id.'/edit'),
        ]);

        $consultas = Consulta::with('afiliado')->latest()->take(6)->get()->map(fn (Consulta $c): array => [
            'tipo'        => 'Consulta',
            'descripcion' => $c->asunto,
            'usuario'     => $c->afiliado?->name ?? 'Sin afiliado',
            'fecha'       => $c->created_at,
            'estado'      => $c->estado,
            'color'       => $c->estado === 'pendiente' ? 'warning' : 'success',
            'url'         => url('/admin/consultas/'.$c->id.'/edit'),
        ]);

        $solicitudes = SolicitudAfiliacion::latest()->take(6)->get()->map(fn (SolicitudAfiliacion $s): array => [
            'tipo'        => 'Afiliación',
            'descripcion' => $s->apellido_nombre.' – '.($s->filial_preferida ?? '—'),
            'usuario'     => $s->email ?? 'Sin email',
            'fecha'       => $s->created_at,
            'estado'      => $s->estado,
            'color'       => $this->colorAfiliacion($s->estado),
            'url'         => url('/admin/solicitudes-afiliacion/'.$s->id.'/edit'),
        ]);

        $turismo = TurismoConsulta::latest()->take(4)->get()->map(fn (TurismoConsulta $t): array => [
            'tipo'        => 'Turismo',
            'descripcion' => $t->nombre.' – '.str_replace('_', ' ', $t->beneficio),
            'usuario'     => $t->telefono ?? $t->email ?? '-',
            'fecha'       => $t->created_at,
            'estado'      => $t->estado,
            'color'       => $t->estado === 'pendiente' ? 'warning' : 'success',
            'url'         => url('/admin/turismo-consultas/'.$t->id.'/edit'),
        ]);

        $posts = Post::with('author')->whereNotNull('published_at')->latest('published_at')->take(4)->get()->map(fn (Post $p): array => [
            'tipo'        => 'Noticia',
            'descripcion' => $p->title,
            'usuario'     => $p->author?->name ?? 'ATSA Tucuman',
            'fecha'       => $p->published_at,
            'estado'      => 'publicada',
            'color'       => 'success',
            'url'         => null,
        ]);

        return [
            'movimientos' => Collection::make()
                ->merge($pedidos)
                ->merge($consultas)
                ->merge($solicitudes)
                ->merge($turismo)
                ->merge($posts)
                ->sortByDesc('fecha')
                ->take(12)
                ->values(),
        ];
    }

    private function colorPedido(string $estado): string
    {
        return match ($estado) {
            'pendiente'   => 'warning',
            'en_revision' => 'info',
            'aprobado'    => 'success',
            'rechazado'   => 'danger',
            'entregado'   => 'gray',
            default       => 'gray',
        };
    }

    private function colorAfiliacion(string $estado): string
    {
        return match ($estado) {
            'pendiente'    => 'warning',
            'en_revision'  => 'info',
            'aprobada'     => 'success',
            'rechazada'    => 'danger',
            default        => 'gray',
        };
    }
}
