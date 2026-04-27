<?php

namespace App\Filament\Resources\TestimonioResource\Pages;

use App\Filament\Resources\TestimonioResource;
use App\Models\Testimonio;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTestimonios extends ListRecords
{
    protected static string $resource = TestimonioResource::class;

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
                ->badge(Testimonio::where('estado', 'pendiente')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'pendiente')),

            'aprobados' => Tab::make('Aprobados')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'aprobado')),

            'rechazados' => Tab::make('Rechazados')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'rechazado')),
        ];
    }
}
