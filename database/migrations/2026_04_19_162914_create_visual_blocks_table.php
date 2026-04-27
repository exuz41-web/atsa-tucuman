<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visual_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('page');
            $table->string('section');
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path');
            $table->string('link_url')->nullable();
            $table->string('link_text')->nullable();
            $table->enum('size', ['small', 'medium', 'large', 'wide'])->default('medium');
            $table->enum('position', ['left', 'right', 'center', 'background'])->default('center');
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['page', 'section', 'active', 'orden']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visual_blocks');
    }
};
