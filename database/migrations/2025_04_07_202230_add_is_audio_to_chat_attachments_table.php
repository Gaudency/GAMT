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
        Schema::table('chat_attachments', function (Blueprint $table) {
            $table->boolean('is_audio')->default(false)->after('file_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_attachments', function (Blueprint $table) {
            $table->dropColumn('is_audio');
        });
    }
};
