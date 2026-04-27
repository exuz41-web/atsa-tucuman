<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('carnets:notificar')->daily();

if (config('backup.enabled', true)) {
    Schedule::command('backups:run')->dailyAt(config('backup.schedule', '02:30'));
}
