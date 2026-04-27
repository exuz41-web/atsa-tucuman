<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\HotelConvenio;

class HotelConvenioResource extends GenericResource
{
    protected static ?string $model = HotelConvenio::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Afiliados';

    protected static ?string $navigationLabel = 'Hoteles convenio';

    protected static ?string $slug = 'hotel-convenios';

    public static function tipos(): array
    {
        return [
            'hotel' => 'Hotel',
            'hosteria' => 'Hostería',
            'apart_hotel' => 'Apart hotel',
            'apart' => 'Apart hotel',
            'complejo' => 'Complejo',
            'complejo_recreativo' => 'Complejo recreativo',
            'camping' => 'Camping',
            'otro' => 'Otro',
        ];
    }
}
