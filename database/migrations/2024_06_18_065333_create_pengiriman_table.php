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
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orders_id')->constrained();
            $table->date('tgl_kirim');
            $table->string('nama_pengirim');
            $table->string('nama_penerima');
            $table->enum('status', ['proses', 'kirim', 'selesai'])->default('proses');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};
