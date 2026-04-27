<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use Filament\Widgets\ChartWidget;

class PedidosPorEstado extends ChartWidget
{
    protected static ?string $heading = 'Pedidos por estado';

    protected static ?string $description = 'Distribucion actual de solicitudes de afiliados';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        $states = [
            'pendiente' => 'Pendiente',
            'en_revision' => 'En revision',
            'aprobado' => 'Aprobado',
            'rechazado' => 'Rechazado',
            'entregado' => 'Entregado',
        ];

        return [
            'datasets' => [[
                'data' => collect(array_keys($states))->map(fn (string $state): int => Pedido::where('estado', $state)->count())->all(),
                'backgroundColor' => ['#f59e0b', '#378ADD', '#1D9E75', '#c0392b', '#8a98a8'],
                'borderWidth' => 0,
            ]],
            'labels' => array_values($states),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
