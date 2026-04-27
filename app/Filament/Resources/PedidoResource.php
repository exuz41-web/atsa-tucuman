<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Pedido;

class PedidoResource extends GenericResource
{
    protected static ?string $model = Pedido::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'Atención al afiliado';

    protected static ?string $navigationLabel = 'Pedidos';

    protected static ?string $slug = 'pedidos';

    public static function tipos(): array
    {
        return [
            'subsidio' => 'Subsidio',
            'bolson' => 'Bolsón',
            'kit_escolar' => 'Kit escolar',
            'nacimiento' => 'Kit de nacimiento',
            'anteojos' => 'Anteojos',
            'medicacion' => 'Medicaciones',
            'turismo' => 'Turismo',
            'ayuda_social' => 'Ayuda social',
            'tramite' => 'Trámite',
            'otro' => 'Otro',
        ];
    }

    public static function estados(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'en_revision' => 'En revisión',
            'aprobado' => 'Aprobado',
            'rechazado' => 'Rechazado',
            'entregado' => 'Entregado',
        ];
    }

    public static function estadoColor(?string $state): string
    {
        return match ($state) {
            'pendiente' => 'warning',
            'en_revision' => 'info',
            'aprobado' => 'success',
            'rechazado' => 'danger',
            'entregado' => 'gray',
            default => 'gray',
        };
    }
}
