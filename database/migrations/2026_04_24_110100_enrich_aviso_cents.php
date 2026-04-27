<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aviso_cents', function (Blueprint $table) {
            $table->json('gallery')->nullable()->after('imagen_path');
            $table->string('video_url')->nullable()->after('gallery');
            $table->string('adjunto_path')->nullable()->after('video_url');
        });
    }

    public function down(): void
    {
        Schema::table('aviso_cents', function (Blueprint $table) {
            $table->dropColumn(['gallery', 'video_url', 'adjunto_path']);
        });
    }
};
