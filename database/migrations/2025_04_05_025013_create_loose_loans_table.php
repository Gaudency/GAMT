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
        Schema::create('loose_loans', function (Blueprint $table) {
            $table->id();
            $table->string('folder_code');
            $table->string('book_title');
            $table->integer('sheets_count');
            $table->string('lender_name'); // Nombre del administrador que presta
            $table->dateTime('loan_date');
            $table->dateTime('return_date');
            $table->text('description')->nullable();
            $table->enum('status', ['loaned', 'returned'])->default('loaned');
            $table->longText('digital_signature')->nullable(); // Almacena la firma como texto codificado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loose_loans');
    }
};
