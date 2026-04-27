<?php

namespace App\Services\Cent;

use Illuminate\Database\Eloquent\Builder;

class FilamentSedeScope
{
    public static function sedeId(): ?int
    {
        $user = auth()->user();
        $role = $user?->cent_role ?: $user?->role;

        if ($user?->cent_sede_id && in_array($role, ['coordinador', 'directivo'], true)) {
            return (int) $user->cent_sede_id;
        }

        return null;
    }

    public static function porColumna(Builder $query, string $column = 'cent_sede_id'): Builder
    {
        return self::sedeId() ? $query->where($column, self::sedeId()) : $query;
    }
}
