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
        Schema::create('hasil_karya', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->string('judul_karya', 255);
            $table->text('deskripsi')->nullable();
            $table->text('karya');
            $table->text('link_karya')->nullable();
            $table->text('keaslian_karya');
            $table->enum('status_karya', ['Belum Diverifikasi', 'Tidak Lolos', 'Lolos Tahap 1', 'Lima Besar'])->default('Belum Diverifikasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_karya');
    }
};
