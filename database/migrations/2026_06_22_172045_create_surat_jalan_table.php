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
        Schema::create('surat_jalan', function (Blueprint $table) {
            $table->id('id_surat_jalan');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_transaksi')->constrained('transaksi_material', 'id_transaksi')->onDelete('cascade');
            $table->string('nomor_surat', 50);
            $table->string('penerima', 100)->nullable();
            $table->date('tanggal_surat');
            $table->string('tujuan', 100)->nullable();
            $table->string('status_surat', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_jalan');
    }
};
