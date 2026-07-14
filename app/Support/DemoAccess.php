<?php

namespace App\Support;

use Database\Seeders\DemoAccessSeeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class DemoAccess
{
    public static function ensure(): void
    {
        if (! self::shouldRun()) {
            return;
        }

        try {
            if (! Schema::hasTable('users') || ! Schema::hasTable('prestadores') || ! Schema::hasTable('pedidos') || ! Schema::hasTable('ordenes_prestacion')) {
                return;
            }

            app(DemoAccessSeeder::class)->run();
        } catch (\Throwable $exception) {
            Log::warning('No se pudieron asegurar los accesos demo.', [
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private static function shouldRun(): bool
    {
        return filter_var(env('RUN_DEMO_SEEDERS', false), FILTER_VALIDATE_BOOL)
            || filter_var(env('DEMO_AUTO_ACCESS', false), FILTER_VALIDATE_BOOL)
            || filled(env('RAILWAY_ENVIRONMENT'))
            || filled(env('RAILWAY_SERVICE_NAME'));
    }
}
