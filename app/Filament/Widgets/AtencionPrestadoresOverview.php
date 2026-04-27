<?php

namespace App\Filament\Widgets;

use App\Models\OrdenPrestacion;
use App\Models\Pedido;
use App\Models\Prestador;
use App\Models\SolicitudBeneficio;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AtencionPrestadoresOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $pedidosPendientes = Pedido::whereIn('estado', ['pendiente', 'en_revision', 'observado'])->count()
            + SolicitudBeneficio::whereIn('estado', ['pendiente', 'en_revision', 'observada'])->count();

        $ordenesPendientes = OrdenPrestacion::whereIn('estado', ['emitida', 'aceptada', 'observada'])->count();
        $entregadasMes = OrdenPrestacion::where('estado', 'entregada')->where('entregada_at', '>=', now()->startOfMonth())->count();
        $prestadoresActivos = Prestador::where('activo', true)->count();
        $demoradas = OrdenPrestacion::whereIn('estado', ['emitida', 'aceptada', 'observada'])->where('created_at', '<=', now()->subDays(7))->count();

        return [
            Stat::make('Atención pendiente', $pedidosPendientes)
                ->description('Pedidos y beneficios por revisar')
                ->descriptionIcon($pedidosPendientes > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($pedidosPendientes > 0 ? 'warning' : 'success')
                ->icon('heroicon-m-inbox-stack'),

            Stat::make('Órdenes pendientes', $ordenesPendientes)
                ->description('Emitidas, aceptadas u observadas')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color($ordenesPendientes > 0 ? 'warning' : 'success')
                ->icon('heroicon-m-clipboard-document-check'),

            Stat::make('Entregadas este mes', $entregadasMes)
                ->description('Confirmadas por prestadores o admin')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->icon('heroicon-m-check-badge'),

            Stat::make('Prestadores activos', $prestadoresActivos)
                ->description('Convenios operativos')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info')
                ->icon('heroicon-m-building-office-2'),

            Stat::make('Demoradas +7 días', $demoradas)
                ->description('Órdenes pendientes antiguas')
                ->descriptionIcon($demoradas > 0 ? 'heroicon-m-clock' : 'heroicon-m-check-circle')
                ->color($demoradas > 0 ? 'danger' : 'success')
                ->icon('heroicon-m-clock'),
        ];
    }
}
