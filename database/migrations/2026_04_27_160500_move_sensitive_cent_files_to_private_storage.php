<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'cent/legajos',
            'cent/cuotas',
            'cent/materiales',
            'cent/trabajos',
            'cent/entregas',
        ] as $directory) {
            foreach (Storage::disk('public')->allFiles($directory) as $path) {
                if (! Storage::disk('local')->exists($path)) {
                    Storage::disk('local')->put($path, Storage::disk('public')->get($path));
                }

                Storage::disk('public')->delete($path);
            }
        }
    }

    public function down(): void
    {
        foreach ([
            'cent/legajos',
            'cent/cuotas',
            'cent/materiales',
            'cent/trabajos',
            'cent/entregas',
        ] as $directory) {
            foreach (Storage::disk('local')->allFiles($directory) as $path) {
                if (! Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->put($path, Storage::disk('local')->get($path));
                }
            }
        }
    }
};
