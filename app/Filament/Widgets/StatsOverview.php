<?php

namespace App\Filament\Widgets;

use App\Models\Consulta;
use App\Models\Pedido;
use App\Models\Post;
use App\Models\SolicitudAfiliacion;
use App\Models\SolicitudBeneficio;
use App\Models\TurismoConsulta;
use App\Models\User;
use Carbon\CarbonImmutable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $afiliados      = $this->safeCount(fn (): int => User::where('role', 'afiliado')->count());
        $afiliadosMes   = $this->safeCount(fn (): int => User::where('role', 'afiliado')->where('created_at', '>=', now()->startOfMonth())->count());

        $pedidosPendientes    = $this->safeCount(fn (): int => Pedido::where('estado', 'pendiente')->count());
        $consultasPendientes  = $this->safeCount(fn (): int => Consulta::where('estado', 'pendiente')->count());

        $solicAfiliacion = $this->safeCount(fn (): int => SolicitudAfiliacion::where('estado', 'pendiente')->count());
        $solicBeneficios = $this->safeCount(fn (): int => SolicitudBeneficio::where('estado', 'pendiente')->count());

        $carnetsPorVencer = $this->safeCount(fn (): int => User::where('carnet_activo', true)
            ->whereNotNull('carnet_vencimiento')
            ->whereBetween('carnet_vencimiento', [now()->startOfDay(), now()->addDays(30)->endOfDay()])
            ->count());

        $turismoConsultas = $this->safeCount(fn (): int => TurismoConsulta::where('estado', 'pendiente')->count());

        $noticias = $this->safeCount(fn (): int => Post::whereNotNull('published_at')->count());

        return [
            // ── Fila 1: afiliados y actividad urgente ──
            Stat::make('Total afiliados', $afiliados)
                ->description("+{$afiliadosMes} altas este mes")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($this->monthlySeries(User::query()->where('role', 'afiliado')))
                ->color('info')
                ->icon('heroicon-m-users'),

            Stat::make('Solicitudes afiliación', $solicAfiliacion)
                ->description($solicAfiliacion > 0 ? 'Pendientes de revisión' : 'Sin pendientes')
                ->descriptionIcon($solicAfiliacion > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->chart($this->monthlySeries(SolicitudAfiliacion::query()->where('estado', 'pendiente')))
                ->color($solicAfiliacion > 0 ? 'warning' : 'success')
                ->icon('heroicon-m-user-plus'),

            Stat::make('Pedidos de beneficios', $pedidosPendientes + $solicBeneficios)
                ->description("Pedidos: {$pedidosPendientes} · Beneficios: {$solicBeneficios}")
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->chart($this->monthlySeries(Pedido::query()->where('estado', 'pendiente')))
                ->color(($pedidosPendientes + $solicBeneficios) > 0 ? 'warning' : 'success')
                ->icon('heroicon-m-gift'),

            Stat::make('Consultas sin responder', $consultasPendientes + $turismoConsultas)
                ->description("Gremiales: {$consultasPendientes} · Turismo: {$turismoConsultas}")
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->chart($this->monthlySeries(Consulta::query()->where('estado', 'pendiente')))
                ->color(($consultasPendientes + $turismoConsultas) > 0 ? 'danger' : 'success')
                ->icon('heroicon-m-bell-alert'),

            // ── Fila 2: carnets y contenido ──
            Stat::make('Carnets por vencer', $carnetsPorVencer)
                ->description('Próximos 30 días')
                ->descriptionIcon($carnetsPorVencer > 0 ? 'heroicon-m-exclamation-circle' : 'heroicon-m-shield-check')
                ->chart($this->carnetsPorVencerSerie())
                ->color($carnetsPorVencer > 5 ? 'danger' : ($carnetsPorVencer > 0 ? 'warning' : 'success'))
                ->icon('heroicon-m-identification'),

            Stat::make('Noticias publicadas', $noticias)
                ->description('Contenido visible en el sitio')
                ->descriptionIcon('heroicon-m-newspaper')
                ->chart($this->monthlySeries(Post::query()->whereNotNull('published_at')))
                ->color('success')
                ->icon('heroicon-m-newspaper'),
        ];
    }

    private function carnetsPorVencerSerie(): array
    {
        // Muestra carnets que vencen en los próximos 7 días por día (últimos 7 días)
        return collect(range(6, 0))->map(function (int $daysAhead): int {
            $day = now()->addDays($daysAhead)->startOfDay();
            return $this->safeCount(fn (): int => User::where('carnet_activo', true)
                ->whereDate('carnet_vencimiento', $day)
                ->count());
        })->all();
    }

    private function monthlySeries($query): array
    {
        return collect(range(5, 0))
            ->map(function (int $monthsAgo) use ($query): int {
                $month = CarbonImmutable::now()->subMonths($monthsAgo)->startOfMonth();

                return (clone $query)->whereBetween('created_at', [$month, $month->endOfMonth()])->count();
            })
            ->all();
    }

    private function safeCount(callable $callback): int
    {
        try {
            return $callback();
        } catch (\Throwable) {
            return 0;
        }
    }
}
