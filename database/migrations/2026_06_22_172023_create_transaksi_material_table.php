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
        Schema::create('transaksi_material', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_material')->constrained('material', 'id_material')->onDelete('cascade');
            $table->enum('jenis_transaksi', ['masuk', 'keluar']);
            $table->integer('jumlah');
            $table->date('tgl_transaksi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_material');
    }
};
