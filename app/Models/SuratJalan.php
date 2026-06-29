<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratJalan extends Model
{
    protected $table = 'surat_jalan';
    protected $primaryKey = 'id_surat_jalan';

    protected $fillable = [
        'id_user',
        'id_transaksi',
        'nomor_surat',
        'penerima',
        'tanggal_surat',
        'tujuan',
        'status_surat',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function transaksiMaterial()
    {
        return $this->belongsTo(TransaksiMaterial::class, 'id_transaksi', 'id_transaksi');
    }
}