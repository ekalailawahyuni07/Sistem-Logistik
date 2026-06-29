<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'area';
    protected $primaryKey = 'id_area';

    protected $fillable = [
        'nama_area',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_area', 'id_area');
    }

    public function cluster()
    {
        return $this->hasMany(Cluster::class, 'id_area', 'id_area');
    }
}