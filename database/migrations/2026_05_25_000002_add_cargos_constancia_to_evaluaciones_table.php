<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->string('cargo_profesor', 255)->nullable()->after('nombre_profesor');
            $table->string('cargo_vobo', 255)->nullable()->after('cargo_profesor');
            $table->string('cargo_destinatario', 255)->nullable()->after('cargo_vobo');
        });
    }

    public function down(): void
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->dropColumn(['cargo_profesor', 'cargo_vobo', 'cargo_destinatario']);
        });
    }
};
