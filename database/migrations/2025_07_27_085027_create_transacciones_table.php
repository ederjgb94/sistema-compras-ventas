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
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            $table->string('folio', 50)->unique();
            $table->enum('tipo', ['ingreso', 'egreso'])->index();
            $table->date('fecha')->index();
            $table->foreignId('contacto_id')->constrained('contactos')->restrictOnDelete();

            // Referencias flexibles (obra/producto)
            $table->enum('referencia_tipo', ['obra', 'producto'])->index();
            $table->string('referencia_nombre')->index();
            $table->jsonb('referencia_datos')->nullable();

            // Facturas flexibles
            $table->enum('factura_tipo', ['manual', 'archivo', 'mixta'])->index();
            $table->string('factura_numero', 100)->nullable()->index();
            $table->jsonb('factura_datos')->nullable();
            $table->jsonb('factura_archivos')->nullable();

            // Datos financieros
            $table->foreignId('metodo_pago_id')->constrained('metodos_pago')->restrictOnDelete();
            $table->string('referencia_pago', 100)->nullable();
            $table->decimal('total', 15, 4)->index();

            // Control
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Índices compuestos para performance
            $table->index(['tipo', 'fecha']);
            $table->index(['fecha', 'total']);
            $table->index(['referencia_tipo', 'referencia_nombre']);
        });

        // Crear índices GIN para JSONB (solo PostgreSQL)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE INDEX idx_transacciones_referencia_gin ON transacciones USING GIN (referencia_datos)');
            DB::statement('CREATE INDEX idx_transacciones_factura_gin ON transacciones USING GIN (factura_datos)');
            DB::statement('CREATE INDEX idx_transacciones_archivos_gin ON transacciones USING GIN (factura_archivos)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};
