<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumentasiTransaksi extends Model
{
    protected $primaryKey = 'id_dokumentasi';

    protected $fillable = [
        'id_transaksi',
        'file_dokumentasi',
        'keterangan',
        'tgl_upload',
    ];

    public function transaksiMaterial()
    {
        return $this->belongsTo(TransaksiMaterial::class, 'id_transaksi', 'id_transaksi');
    }
}