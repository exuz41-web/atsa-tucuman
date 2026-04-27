<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes_afiliacion', function (Blueprint $table) {
            if (! Schema::hasColumn('solicitudes_afiliacion', 'public_token')) {
                $table->uuid('public_token')->nullable()->after('pdf_path')->unique();
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'cent_public_token')) {
                $table->uuid('cent_public_token')->nullable()->after('carnet_emitido_at')->unique();
            }
        });

        DB::table('solicitudes_afiliacion')
            ->whereNull('public_token')
            ->orderBy('id')
            ->get(['id'])
            ->each(fn (object $row) => DB::table('solicitudes_afiliacion')
                ->where('id', $row->id)
                ->update(['public_token' => (string) Str::uuid()]));

        DB::table('users')
            ->whereNull('cent_public_token')
            ->where(function ($query) {
                $query->where('cent_role', 'alumno')
                    ->orWhere('role', 'alumno');
            })
            ->orderBy('id')
            ->get(['id'])
            ->each(fn (object $row) => DB::table('users')
                ->where('id', $row->id)
                ->update(['cent_public_token' => (string) Str::uuid()]));
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'cent_public_token')) {
                $table->dropUnique(['cent_public_token']);
                $table->dropColumn('cent_public_token');
            }
        });

        Schema::table('solicitudes_afiliacion', function (Blueprint $table) {
            if (Schema::hasColumn('solicitudes_afiliacion', 'public_token')) {
                $table->dropUnique(['public_token']);
                $table->dropColumn('public_token');
            }
        });
    }
};
