<?php

namespace App\Filament\Concerns;

trait HasResourcePermissionAccess
{
    protected static function permissionPanel(): string
    {
        return static::$panelScope ?? \Filament\Facades\Filament::getCurrentPanel()?->getId() ?? 'admin';
    }

    protected static function canPerformResourceAction(string $action): bool
    {
        $user = auth()->user();

        if (! $user || ! method_exists($user, 'hasResourcePermission')) {
            return false;
        }

        return $user->hasResourcePermission(static::class, static::permissionPanel(), $action);
    }

    public static function canViewAny(): bool
    {
        return static::canPerformResourceAction('view');
    }

    public static function canCreate(): bool
    {
        return static::canPerformResourceAction('create');
    }

    public static function canEdit($record): bool
    {
        return static::canPerformResourceAction('edit');
    }

    public static function canDelete($record): bool
    {
        return static::canPerformResourceAction('delete');
    }
}
