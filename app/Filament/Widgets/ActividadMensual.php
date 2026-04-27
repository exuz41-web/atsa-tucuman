<?php

namespace App\Filament\Widgets;

use App\Models\Consulta;
use App\Models\Pedido;
use App\Models\SolicitudAfiliacion;
use App\Models\User;
use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;

class ActividadMensual extends ChartWidget
{
    protected static ?string $heading = 'Actividad mensual';

    protected static ?string $description = 'Nuevos afiliados, pedidos y consultas — últimos 6 meses';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 2;

    protected static string $color = 'primary';

    protected function getData(): array
    {
        $months = collect(range(5, 0))
            ->map(fn (int $monthsAgo): CarbonImmutable => CarbonImmutable::now()->subMonths($monthsAgo)->startOfMonth());

        $countByMonth = fn ($query) => $months
            ->map(fn (CarbonImmutable $m): int => (clone $query)
                ->whereBetween('created_at', [$m, $m->endOfMonth()])
                ->count())
            ->all();

        return [
            'datasets' => [
                [
                    'label'           => 'Nuevos afiliados',
                    'data'            => $countByMonth(User::query()->where('role', 'afiliado')),
                    'backgroundColor' => '#1e3a5f',
                    'borderColor'     => '#1e3a5f',
                    'borderRadius'    => 6,
                    'order'           => 1,
                ],
                [
                    'label'           => 'Pedidos',
                    'data'            => $countByMonth(Pedido::query()),
                    'backgroundColor' => '#378ADD',
                    'borderColor'     => '#378ADD',
                    'borderRadius'    => 6,
                    'order'           => 2,
                ],
                [
                    'label'           => 'Consultas',
                    'data'            => $countByMonth(Consulta::query()),
                    'backgroundColor' => '#49beff',
                    'borderColor'     => '#49beff',
                    'borderRadius'    => 6,
                    'order'           => 3,
                ],
                [
                    'label'           => 'Solicitudes afiliación',
                    'data'            => $countByMonth(SolicitudAfiliacion::query()),
                    'backgroundColor' => '#f59e0b',
                    'borderColor'     => '#f59e0b',
                    'borderRadius'    => 6,
                    'order'           => 4,
                ],
            ],
            'labels' => $months->map(fn (CarbonImmutable $m): string => $this->monthLabel($m))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    private function monthLabel(CarbonImmutable $month): string
    {
        return [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic',
        ][(int) $month->format('n')];
    }
}
