<?php

namespace App\Filament\Resources\ConsultaResource\Pages;

use App\Filament\Resources\ConsultaResource;
use App\Models\Consulta;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListConsultas extends ListRecords
{
    protected static string $resource = ConsultaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos'),

            'pendientes' => Tab::make('Pendientes')
                ->badge(Consulta::where('estado', 'pendiente')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'pendiente')),

            'en_proceso' => Tab::make('En proceso')
                ->badge(Consulta::where('estado', 'en_proceso')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'en_proceso')),

            'respondidas' => Tab::make('Respondidas')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'respondida')),

            'cerradas' => Tab::make('Cerradas')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'cerrada')),
        ];
    }
}
