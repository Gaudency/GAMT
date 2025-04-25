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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('N_codigo')->nullable();
            $table->string('title')->nullable();
            $table->string('ambiente')->nullable();
            $table->string('bandeja')->nullable();
            $table->string('ubicacion')->nullable();
            $table->year('year')->nullable();
            $table->string('description')->nullable();
            $table->string('tomo')->nullable();
            $table->string('N_hojas')->nullable();
            $table->string('book_img')->nullable();
            $table->string('pdf_file')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->enum('estado', ['Prestado', 'No Prestado'])->default('No Prestado');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations congratulation css + bootstrap y tailwind css.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
