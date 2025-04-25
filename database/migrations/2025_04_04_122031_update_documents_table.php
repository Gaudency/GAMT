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
        Schema::table('documents', function (Blueprint $table) {
            // Eliminar la restricción de clave foránea existente
            $table->dropForeign(['user_id']);

            // Modificar la columna para permitir valores NULL
            $table->foreignId('user_id')->nullable()->change();

            // Agregar una nueva clave foránea sin el comportamiento CASCADE
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Eliminar la restricción de clave foránea modificada
            $table->dropForeign(['user_id']);

            // Restaurar la restricción original con CASCADE
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Eliminar el permiso de NULL
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
