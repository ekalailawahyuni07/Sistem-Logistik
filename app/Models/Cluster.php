<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    protected $table = 'cluster';

    protected $primaryKey = 'id_cluster';

    protected $fillable = [
        'id_area',
        'kode_cluster',
        'nama_cluster',
    ];

    public function area()
    {
        return $this->belongsTo(
            Area::class,
            'id_area',
            'id_area'
        );
    }

    public function materials()
    {
        return $this->hasMany(
            Material::class,
            'id_cluster',
            'id_cluster'
        );
    }

    public function transaksiMaterial()
    {
        return $this->hasMany(
            TransaksiMaterial::class,
            'id_cluster',
            'id_cluster'
        );
    }
}