<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            if (! Schema::hasColumn('documentos', 'archivo')) {
                $table->string('archivo')->nullable()->after('id_semestre');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            if (Schema::hasColumn('documentos', 'archivo')) {
                $table->dropColumn('archivo');
            }
        });
    }
};
