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
        return $this->belongsTo(Area::class, 'id_area', 'id_area');
    }
}