<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DokumentasiTransaksi;

class TransaksiMaterial extends Model
{
    protected $table = 'transaksi_material';

    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_user',
        'id_material',
        'id_cluster',
        'id_area',
        'jenis_transaksi',
        'jumlah',
        'tgl_transaksi',
        'no_bukti',
        'project',
        'nama_penerima',
        'nama_sopir',
        'no_hp',
        'perusahaan',
        'kendaraan',
        'plat_nomor',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'tgl_transaksi' => 'date',
    ];

    public function area()
    {
        return $this->belongsTo(
            Area::class,
            'id_area',
            'id_area'
        );
    }

    public function material()
    {
        return $this->belongsTo(
            Material::class,
            'id_material',
            'id_material'
        );
    }

    public function cluster()
    {
        return $this->belongsTo(
            Cluster::class,
            'id_cluster',
            'id_cluster'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }

    public function dokumentasiTransaksi()
    {
        return $this->hasMany(
            DokumentasiTransaksi::class,
            'id_transaksi',
            'id_transaksi'
        );
    }
    public function dokumentasi()
    {
        return $this->hasMany(
            DokumentasiTransaksi::class,
            'id_transaksi',
            'id_transaksi'
        );
    }
}