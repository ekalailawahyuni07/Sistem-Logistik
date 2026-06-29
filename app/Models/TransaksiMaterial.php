<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiMaterial extends Model
{
    protected $table = 'transaksi_material';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_user',
        'id_material',
        'jenis_transaksi',
        'jumlah',
        'tgl_transaksi',
        'no_bukti',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }

    public function dokumentasiTransaksi()
    {
        return $this->hasMany(DokumentasiTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function suratJalan()
    {
        return $this->hasMany(SuratJalan::class, 'id_transaksi', 'id_transaksi');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_transaksi', 'id_transaksi');
    }
}