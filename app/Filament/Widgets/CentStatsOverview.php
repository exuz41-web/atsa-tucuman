<?php

namespace App\Filament\Widgets;

use App\Models\CentSede;
use App\Models\MatriculaCent;
use App\Models\PreinscripcionCent;
use App\Models\User;
use Carbon\CarbonImmutable;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CentStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'cent';
    }

    protected function getStats(): array
    {
        $preinscripcionesPendientes = $this->safeCount(
            fn (): int => PreinscripcionCent::where('estado', 'pendiente')->count()
        );

        $matriculasActivas = $this->safeCount(
            fn (): int => MatriculaCent::whereIn('estado', ['inscripto', 'cursando'])->count()
        );

        $docentes = $this->safeCount(
            fn (): int => User::where('cent_role', 'docente')->orWhere('role', 'docente')->count()
        );

        $sedes = $this->safeCount(
            fn (): int => CentSede::where('activa', true)->count()
        );

        return [
            Stat::make('Preinscripciones pendientes', $preinscripcionesPendientes)
                ->description('A revisar por administración')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->chart($this->monthlySeries(PreinscripcionCent::query()->where('estado', 'pendiente')))
                ->color($preinscripcionesPendientes > 0 ? 'warning' : 'success')
                ->icon('heroicon-m-document-plus'),

            Stat::make('Matrículas activas', $matriculasActivas)
                ->description('Alumnos inscriptos o cursando')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($this->monthlySeries(MatriculaCent::query()))
                ->color('success')
                ->icon('heroicon-m-academic-cap'),

            Stat::make('Docentes', $docentes)
                ->description('Usuarios con rol docente')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart($this->monthlySeries(User::query()->where(function ($query) {
                    $query->where('cent_role', 'docente')->orWhere('role', 'docente');
                })))
                ->color('info')
                ->icon('heroicon-m-users'),

            Stat::make('Sedes activas', $sedes)
                ->description('Filiales educativas del CENT')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('primary')
                ->icon('heroicon-m-building-office-2'),
        ];
    }

    private function monthlySeries($query): array
    {
        return collect(range(5, 0))
            ->map(function (int $monthsAgo) use ($query): int {
                $month = CarbonImmutable::now()->subMonths($monthsAgo)->startOfMonth();

                return (clone $query)
                    ->whereBetween('created_at', [$month, $month->endOfMonth()])
                    ->count();
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
