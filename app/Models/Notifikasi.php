<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';

    protected $fillable = [
        'id_user',
        'id_transaksi',
        'pesan',
        'status_baca',
        'tgl_notifikasi',
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