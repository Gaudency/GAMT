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
        Schema::table('document_comprobante', function (Blueprint $table) {
            $table->text('observaciones_prestamo')->nullable()->after('fecha_devolucion');
            $table->text('observaciones_devolucion')->nullable()->after('observaciones_prestamo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_comprobante', function (Blueprint $table) {
            $table->dropColumn(['observaciones_prestamo', 'observaciones_devolucion']);
        });
    }
};
