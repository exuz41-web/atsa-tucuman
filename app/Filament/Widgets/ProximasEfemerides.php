<?php

namespace App\Filament\Widgets;

use App\Models\Efemeride;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class ProximasEfemerides extends Widget
{
    protected static string $view = 'filament.widgets.proximas-efemerides';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 2;

    protected function getViewData(): array
    {
        $todayKey = ((int) now()->format('n')) * 100 + ((int) now()->format('j'));

        $items = Efemeride::where('activo', true)
            ->get()
            ->sortBy(fn (Efemeride $efemeride): int => (($efemeride->mes * 100 + $efemeride->dia) < $todayKey ? 1300 : 0) + ($efemeride->mes * 100 + $efemeride->dia))
            ->take(3)
            ->values();

        return [
            'efemerides' => $items,
            'meses' => Collection::make([1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre']),
        ];
    }
}
