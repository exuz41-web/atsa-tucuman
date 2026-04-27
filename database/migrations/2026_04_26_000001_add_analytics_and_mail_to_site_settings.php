<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('google_analytics_id')->nullable()->after('tiktok_url')
                ->comment('Google Analytics 4 Measurement ID. Ej: G-XXXXXXXXXX');
            $table->string('mail_from_address')->nullable()->after('google_analytics_id')
                ->comment('Dirección de correo remitente para notificaciones del sitio');
            $table->string('mail_from_name')->nullable()->after('mail_from_address');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['google_analytics_id', 'mail_from_address', 'mail_from_name']);
        });
    }
};
