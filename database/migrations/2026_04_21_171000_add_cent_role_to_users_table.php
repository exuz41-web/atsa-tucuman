<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'cent_role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('cent_role')->nullable()->after('role')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'cent_role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('cent_role');
            });
        }
    }
};
