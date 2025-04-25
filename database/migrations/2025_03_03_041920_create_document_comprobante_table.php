<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('document_comprobante', function (Blueprint $table) {
            $table->id();

            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->foreignId('comprobante_id')->constrained()->onDelete('cascade');
            $table->date('fecha_prestamo');
            $table->date('fecha_devolucion')->nullable();
            $table->enum('estado', ['prestado', 'devuelto'])->default('prestado');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('document_comprobante');
    }
};
