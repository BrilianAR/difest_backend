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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->string('id', 20)->primary();
            $table->unsignedBigInteger('pendaftaran_id');
            $table->integer('harga');
            $table->string('bukti_pembayaran');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('pendaftaran_id')->references('id')->on('pendaftarans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
