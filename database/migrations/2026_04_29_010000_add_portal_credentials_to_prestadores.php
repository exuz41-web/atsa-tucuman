<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prestadores', function (Blueprint $table): void {
            $table->string('portal_username')->nullable()->unique()->after('portal_token');
            $table->string('portal_password')->nullable()->after('portal_username');
            $table->timestamp('portal_last_login_at')->nullable()->after('portal_password');
        });
    }

    public function down(): void
    {
        Schema::table('prestadores', function (Blueprint $table): void {
            $table->dropColumn(['portal_username', 'portal_password', 'portal_last_login_at']);
        });
    }
};
