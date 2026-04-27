<?php

namespace App\Filament\Resources\BeneficioResource\Pages;

use App\Filament\Resources\BeneficioResource;
use App\Models\Beneficio;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListBeneficios extends ListRecords
{
    protected static string $resource = BeneficioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = ['todos' => Tab::make('Todos')];

        foreach (Beneficio::categorias() as $key => $label) {
            $tabs[$key] = Tab::make($label)
                ->badge(Beneficio::where('categoria', $key)->count())
                ->modifyQueryUsing(fn (Builder $q) => $q->where('categoria', $key));
        }

        return $tabs;
    }
}
