<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends GenericResource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Padrón sindical';

    protected static ?string $navigationLabel = 'Afiliados';

    protected static ?string $slug = 'users';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where(function (Builder $query): void {
            $query->where('role', 'afiliado')->orWhereNotNull('numero_afiliado');
        });
    }
}
