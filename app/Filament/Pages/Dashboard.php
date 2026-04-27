<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ActividadMensual;
use App\Filament\Widgets\AtencionPrestadoresOverview;
use App\Filament\Widgets\PedidosPorEstado;
use App\Filament\Widgets\ProximasEfemerides;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\UltimasActividades;
use App\Filament\Widgets\UltimosMovimientos;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Panel de administración';

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            AtencionPrestadoresOverview::class,
            PedidosPorEstado::class,
            ActividadMensual::class,
            UltimosMovimientos::class,
            ProximasEfemerides::class,
            UltimasActividades::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'lg' => 2,
            'xl' => 4,
        ];
    }
}
