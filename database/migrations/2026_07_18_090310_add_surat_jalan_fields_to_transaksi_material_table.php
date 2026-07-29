<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_material', function (Blueprint $table) {

            $table->string('no_hp')->nullable()->after('nama_penerima');
            $table->string('perusahaan')->nullable()->after('no_hp');
            $table->text('alamat_tujuan')->nullable()->after('perusahaan');
            $table->string('nama_sopir')->nullable()->after('alamat_tujuan');
            $table->string('kendaraan')->nullable()->after('nama_sopir');
            $table->string('plat_nomor')->nullable()->after('kendaraan');

        });
    }

    public function down(): void
    {
        Schema::table('transaksi_material', function (Blueprint $table) {

            $table->dropColumn([
                'no_hp',
                'perusahaan',
                'nama_sopir',
                'kendaraan',
                'plat_nomor',
            ]);

        });
    }
};