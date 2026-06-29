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
        Schema::create('dokumentasi_transaksi', function (Blueprint $table) {
            $table->id('id_dokumentasi');
            $table->foreignId('id_transaksi')->constrained('transaksi_material', 'id_transaksi')->onDelete('cascade');
            $table->string('file_dokumentasi', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tgl_upload');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_transaksi');
    }
};
