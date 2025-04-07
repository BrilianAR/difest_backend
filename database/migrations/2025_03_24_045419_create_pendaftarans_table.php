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
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('lomba_id');
            $table->string('nama_ketua');
            $table->string('email')->unique();
            $table->string('no_hp', 15);
            $table->string('asal_institusi');
            $table->string('kartu_identitas_ketua'); // Path ke file KTP/Kartu Pelajar
            $table->string('nama_team')->nullable();

            for ($i = 1; $i <= 4; $i++) {
                $table->string("nama_anggota_$i")->nullable();
                $table->string("asal_institusi_anggota_$i")->nullable();
                $table->string("kartu_identitas_anggota_$i")->nullable(); // Path ke file KTP/Kartu Pelajar
            }
            // Bukti Pembayaran
            $table->string('bukti_pembayaran')->nullable();

            // Bukti follow/screenshot media sosial
            $table->string('bukti_follow_ig_difest')->nullable();
            $table->string('bukti_follow_ig_himatikom')->nullable();
            $table->string('bukti_follow_tiktok_difest')->nullable();
            $table->string('bukti_subscribe_youtube_himatikom')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lomba_id')->references('id')->on('lomba')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
