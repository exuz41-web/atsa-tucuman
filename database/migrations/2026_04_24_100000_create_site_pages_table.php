<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();   // 'home', 'sindicato', 'gremial', etc.
            $table->string('label');             // 'Inicio', 'Sindicato', etc.
            $table->string('icon')->default('heroicon-o-document');
            $table->json('blocks')->nullable();  // Builder JSON
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_pages');
    }
};
