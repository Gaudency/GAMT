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
        Schema::table('books', function (Blueprint $table) {
            $table->string('ambiente_nombre')->nullable()->after('ambiente');
            $table->integer('estante_numero')->nullable()->after('bandeja');
            $table->integer('bandeja_numero')->nullable()->after('estante_numero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['ambiente_nombre', 'estante_numero', 'bandeja_numero']);
        });
    }
};
