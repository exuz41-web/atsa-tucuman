<?php

return [
    'enabled' => env('BACKUP_ENABLED', true),

    'schedule' => env('BACKUP_SCHEDULE', '02:30'),

    'keep_days' => (int) env('BACKUP_KEEP_DAYS', 14),
];
