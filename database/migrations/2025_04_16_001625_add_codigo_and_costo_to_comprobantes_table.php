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
        Schema::table('comprobantes', function (Blueprint $table) {
            // Campo para códigos como "1234589N°1"
            $table->string('codigo_personalizado')->nullable()->after('numero_comprobante')
                  ->comment('Código personalizado del comprobante (ej: 1234589N°1)');

            // Campo para registrar costos o valores devengados
            $table->decimal('costo', 10, 2)->nullable()->after('descripcion')
                  ->comment('Valor monetario o costo devengado asociado al comprobante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comprobantes', function (Blueprint $table) {
            $table->dropColumn('codigo_personalizado');
            $table->dropColumn('costo');
        });
    }
};
