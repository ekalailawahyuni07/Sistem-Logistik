<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'material';

    protected $primaryKey = 'id_material';

    protected $fillable = [
        'id_cluster',
        'kode_material',
        'nama_material',
        'project',
        'jenis_material',
        'satuan',
        'stok',
        'keterangan',
    ];

    protected $casts = [
        'stok' => 'integer',
    ];

    public function cluster()
    {
        return $this->belongsTo(
            Cluster::class,
            'id_cluster',
            'id_cluster'
        );
    }

    public function transaksiMaterial()
    {
        return $this->hasMany(
            TransaksiMaterial::class,
            'id_material',
            'id_material'
        );
    }
}