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
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'afiliado_public_token')) {
                $table->uuid('afiliado_public_token')->nullable()->after('carnet_emitido_at')->unique();
            }
        });

        DB::table('users')
            ->whereNull('afiliado_public_token')
            ->where(function ($query): void {
                $query->where('role', 'afiliado')
                    ->orWhereNotNull('numero_afiliado');
            })
            ->orderBy('id')
            ->get(['id'])
            ->each(fn (object $row) => DB::table('users')
                ->where('id', $row->id)
                ->update(['afiliado_public_token' => (string) Str::uuid()]));
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (Schema::hasColumn('users', 'afiliado_public_token')) {
                $table->dropUnique(['afiliado_public_token']);
                $table->dropColumn('afiliado_public_token');
            }
        });
    }
};
