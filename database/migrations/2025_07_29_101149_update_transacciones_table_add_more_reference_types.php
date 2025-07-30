<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero eliminar la restricción existente
        Schema::table('transacciones', function (Blueprint $table) {
            // Hacer contacto_id nullable
            $table->foreignId('contacto_id')->nullable()->change();
        });

        // Actualizar el enum para incluir más tipos (PostgreSQL)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TYPE transacciones_referencia_tipo_enum ADD VALUE IF NOT EXISTS 'servicio'");
            DB::statement("ALTER TYPE transacciones_referencia_tipo_enum ADD VALUE IF NOT EXISTS 'otro'");
        } else {
            // Para SQLite, recrear la columna
            DB::statement("UPDATE transacciones SET referencia_tipo = 'obra' WHERE referencia_tipo NOT IN ('obra', 'producto')");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacer nada en el rollback para evitar pérdida de datos
    }
};
