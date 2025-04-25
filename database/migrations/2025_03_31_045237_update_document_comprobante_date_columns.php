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
            // Modificar las columnas de fecha para que sean datetime en lugar de date
            $table->dateTime('fecha_prestamo')->change();
            $table->dateTime('fecha_devolucion')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_comprobante', function (Blueprint $table) {
            // Revertir a tipo date
            $table->date('fecha_prestamo')->change();
            $table->date('fecha_devolucion')->nullable()->change();
        });
    }
};
