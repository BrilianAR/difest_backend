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
        Schema::create('juris', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedBigInteger('lomba_id');
            $table->timestamps();

            $table->foreign('lomba_id')->references('id')->on('lomba')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juris');
    }
};
