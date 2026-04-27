<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;

class ActividadChart extends ChartWidget
{
    protected static ?string $heading = 'Actividad mensual';

    protected static ?string $description = 'Pedidos recibidos durante los últimos 6 meses';

    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    protected static string $color = 'primary';

    protected static ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(
            fn (int $monthsAgo): CarbonImmutable => CarbonImmutable::now()
                ->subMonths($monthsAgo)
                ->startOfMonth()
        );

        return [
            'datasets' => [
                [
                    'label' => 'Pedidos',
                    'data' => $months->map(fn (CarbonImmutable $month): int => Pedido::query()
                        ->whereBetween('created_at', [$month, $month->endOfMonth()])
                        ->count())
                        ->all(),
                    'backgroundColor' => '#378ADD',
                    'borderColor' => '#1e3a5f',
                    'borderRadius' => 8,
                    'borderWidth' => 1,
                    'hoverBackgroundColor' => '#1e3a5f',
                ],
            ],
            'labels' => $months->map(fn (CarbonImmutable $month): string => $this->monthLabel($month))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                    'grid' => [
                        'color' => '#e5eaef',
                    ],
                ],
            ],
        ];
    }

    private function monthLabel(CarbonImmutable $month): string
    {
        $labels = [
            1 => 'Ene',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Abr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Ago',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dic',
        ];

        return $labels[(int) $month->format('n')];
    }
}
