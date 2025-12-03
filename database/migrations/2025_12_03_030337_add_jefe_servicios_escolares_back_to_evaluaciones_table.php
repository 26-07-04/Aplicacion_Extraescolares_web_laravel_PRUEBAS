<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Shim migration to satisfy rollback. No schema changes performed.
     */
    public function up(): void
    {
        // No-op. Column is handled in the consolidated create migration.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op to avoid unintended schema changes during rollback.
    }
};
