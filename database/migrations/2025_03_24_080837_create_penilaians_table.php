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
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->decimal('nilai');
            $table->foreignId('karya_id')->references('id')->on('hasil_karya')->onDelete('cascade');
            $table->foreignId('daftar_kriteria_id')->references('id')->on('daftar_kriterias')->onDelete('cascade');
            $table->foreignId('juri_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};