<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── POSTS ─────────────────────────────────────────────────────────────
        Schema::table('posts', function (Blueprint $table) {
            $table->json('gallery')->nullable()->after('image');        // múltiples imágenes
            $table->string('video_url')->nullable()->after('gallery');  // YouTube / Vimeo
            $table->json('tags')->nullable()->after('video_url');       // etiquetas
            $table->string('meta_description')->nullable()->after('tags'); // SEO
            $table->string('fuente')->nullable()->after('meta_description'); // fuente / crédito
            $table->string('fuente_url')->nullable()->after('fuente');
        });

        // ── EFEMERIDES ────────────────────────────────────────────────────────
        Schema::table('efemerides', function (Blueprint $table) {
            $table->string('imagen_path')->nullable()->after('descripcion');
            $table->string('fuente')->nullable()->after('imagen_path');
            $table->string('fuente_url')->nullable()->after('fuente');
            $table->string('enlace_externo')->nullable()->after('fuente_url');
            $table->string('anio')->nullable()->after('enlace_externo'); // año del hecho histórico
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['gallery', 'video_url', 'tags', 'meta_description', 'fuente', 'fuente_url']);
        });

        Schema::table('efemerides', function (Blueprint $table) {
            $table->dropColumn(['imagen_path', 'fuente', 'fuente_url', 'enlace_externo', 'anio']);
        });
    }
};
