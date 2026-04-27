<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;

class PedidosChart extends ChartWidget
{
    protected static ?string $heading = 'Pedidos por mes';

    protected static ?int $sort = 2;

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
                    'backgroundColor' => '#1e3a5f',
                    'borderColor' => '#378ADD',
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $months->map(fn (CarbonImmutable $month): string => $this->monthLabel($month))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    private function monthLabel(CarbonImmutable $month): string
    {
        return [
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
        ][(int) $month->format('n')];
    }
}
