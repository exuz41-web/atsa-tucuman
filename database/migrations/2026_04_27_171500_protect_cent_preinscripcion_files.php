<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('preinscripciones_cent', function (Blueprint $table) {
            if (! Schema::hasColumn('preinscripciones_cent', 'public_token')) {
                $table->uuid('public_token')->nullable()->after('codigo')->unique();
            }
        });

        DB::table('preinscripciones_cent')
            ->whereNull('public_token')
            ->orderBy('id')
            ->get(['id'])
            ->each(fn (object $row) => DB::table('preinscripciones_cent')
                ->where('id', $row->id)
                ->update(['public_token' => (string) Str::uuid()]));

        foreach (Storage::disk('public')->allFiles('cent/preinscripciones') as $path) {
            if (! Storage::disk('local')->exists($path)) {
                Storage::disk('local')->put($path, Storage::disk('public')->get($path));
            }

            Storage::disk('public')->delete($path);
        }
    }

    public function down(): void
    {
        foreach (Storage::disk('local')->allFiles('cent/preinscripciones') as $path) {
            if (! Storage::disk('public')->exists($path)) {
                Storage::disk('public')->put($path, Storage::disk('local')->get($path));
            }
        }

        Schema::table('preinscripciones_cent', function (Blueprint $table) {
            if (Schema::hasColumn('preinscripciones_cent', 'public_token')) {
                $table->dropUnique(['public_token']);
                $table->dropColumn('public_token');
            }
        });
    }
};
