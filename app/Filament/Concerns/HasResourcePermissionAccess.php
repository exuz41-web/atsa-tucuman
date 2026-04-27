<?php

namespace App\Filament\Concerns;

trait HasResourcePermissionAccess
{
    protected static function permissionPanel(): string
    {
        $panelScope = property_exists(static::class, 'panelScope') ? static::$panelScope : null;

        return $panelScope ?? \Filament\Facades\Filament::getCurrentPanel()?->getId() ?? 'admin';
    }

    protected static function matchesPanelScope(): bool
    {
        $panelScope = property_exists(static::class, 'panelScope') ? static::$panelScope : null;

        return $panelScope === null || \Filament\Facades\Filament::getCurrentPanel()?->getId() === $panelScope;
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

    public static function canAccess(): bool
    {
        return static::matchesPanelScope() && static::canViewAny();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
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
