<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lomba', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lomba', 255);
            $table->text('deskripsi');
            $table->integer('harga');
            $table->enum('jenis_pengumpulan', ['zip/rar', 'link', 'dokumen(pdf,word)']);
            $table->string('logo_lomba', 255);
            $table->enum('jenis_lomba', ['individu', 'kelompok']);
            $table->enum('kategori_lomba', ['difest', 'robofest']);
            $table->integer('no_pj');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lomba');
    }
};
