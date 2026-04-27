<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pedidos MODIFY tipo ENUM('subsidio', 'bolson', 'kit_escolar', 'nacimiento', 'anteojos', 'protesis', 'medicacion', 'medicamentos', 'ayuda_social', 'ayuda_economica', 'turismo', 'tramite', 'otro') NOT NULL");
    }

    public function down(): void
    {
        DB::table('pedidos')
            ->whereNotIn('tipo', ['anteojos', 'protesis', 'medicamentos', 'ayuda_economica', 'otro'])
            ->update(['tipo' => 'otro']);

        DB::statement("ALTER TABLE pedidos MODIFY tipo ENUM('anteojos', 'protesis', 'medicamentos', 'ayuda_economica', 'otro') NOT NULL");
    }
};
