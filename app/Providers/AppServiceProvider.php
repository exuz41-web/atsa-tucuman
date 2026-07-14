<?php

namespace App\Providers;

use App\Support\MailSettings;
use App\Support\DemoAccess;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production') || filled(env('RAILWAY_ENVIRONMENT')) || filled(env('RAILWAY_SERVICE_NAME'))) {
            URL::forceScheme('https');
        }

        MailSettings::apply();
        DemoAccess::ensure();
    }
}
