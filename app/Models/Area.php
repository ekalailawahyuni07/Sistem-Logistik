<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TransaksiMaterial;

class Area extends Model
{
    protected $table = 'area';

    protected $primaryKey = 'id_area';

    protected $fillable = [
        'kode_area',
        'nama_area',
    ];

    public function users()
    {
        return $this->hasMany(
            User::class,
            'id_area',
            'id_area'
        );
    }

    public function clusters()
    {
        return $this->hasMany(
            Cluster::class,
            'id_area',
            'id_area'
        );
    }

    public function transaksiMaterial()
    {
        return $this->hasMany(
            TransaksiMaterial::class,
            'id_area',
            'id_area'
        );
    }
}