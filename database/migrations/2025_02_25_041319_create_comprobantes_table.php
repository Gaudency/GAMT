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
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->integer('numero_comprobante');  // Número específico del comprobante dentro del libro
            $table->integer('n_hojas');            // Número de hojas del comprobante individual
            $table->string('pdf_file')->nullable(); // PDF específico del comprobante
            $table->text('descripcion')->nullable(); // Descripción del comprobante
            $table->enum('estado', ['activo', 'inactivo'])->default('activo'); // Información adicional específica
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};
