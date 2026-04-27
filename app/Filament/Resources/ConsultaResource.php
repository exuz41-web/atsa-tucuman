<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Consulta;

class ConsultaResource extends GenericResource
{
    protected static ?string $model = Consulta::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Atención al afiliado';

    protected static ?string $navigationLabel = 'Consultas';

    protected static ?string $slug = 'consultas';

    public static function tipos(): array
    {
        return [
            'consulta' => 'Consulta',
            'turno' => 'Turno',
            'asesoramiento' => 'Asesoramiento',
            'beneficio' => 'Beneficio',
            'gremial' => 'Gremial',
            'otro' => 'Otro',
        ];
    }

    public static function estados(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'en_proceso' => 'En proceso',
            'respondida' => 'Respondida',
            'cerrada' => 'Cerrada',
        ];
    }
}
